<?php

namespace App\Http\Controllers\Api;

use App\Events\Api\OnOrderCreated;
use App\Events\Api\OnOrderPaymentStatusChanged;
use App\Events\Api\OnOrderUpdated;
use App\Http\Requests\Api\OrderCreateRequest;
use App\Http\Requests\Api\UpdateOrderRequest;
use App\Models\Address;
use App\Models\Order;
use App\Models\ProductNew;
use App\Service\Billing\Contracts\BillingServiceContract;
use App\Service\Billing\Exception\AlreadyProgressOrderException;
use App\Service\Billing\Exception\RegisterOrderException;
use const Grpc\STATUS_CANCELLED;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function check(int $id): JsonResponse
    {
        $order = Order::with(['user','address','products' => function ($q) {
            $q->withPivot(['product_count']);
        }])->where(function ($query) use ($id) {
            $query->where('user_id', Auth::id())
                ->where('id', $id);
        })->first();

        if (! $order) {
            throw new NotFoundHttpException("Order not found!");
        }

        return response()->json($order);
    }

    public function store(OrderCreateRequest $request): JsonResponse
    {
        /**
         * @var $order Order
         */
        $order = new Order(array_merge($request->input(), [
            'user_id' => Auth::id(),
            'date_of_delivery_variants' => [],
            'status' => Order::STATUS_NEW,
        ]));
        $order->litrs = $order->bottles * Order::ONE_BOTTLE_LITRS;
        $order->save();

        if ($request->has('products')) {
            foreach ($request->input('products') as $product) {
                $order->products()->attach($product['id'], [
                    'product_count' => $product['count'],
                ]);
            }
        }

        $amount = 0;
        /** @var ProductNew $product */
        foreach ($order->products as $product) {
            $qty = $product->pivot->product_count;
            $amount += $product->price($qty) * $qty;
        }
        $order->update(['price' => $amount]);

        event(new OnOrderCreated($order));
        Log::info('Get order time from AP: #'.$order->id, $order->toArray());

        return response()->json($order);
    }

    public function update($id, UpdateOrderRequest $request): JsonResponse
    {
        /**
         * @var $order Order
         */
        $order = Order::with(['user','address','products' => function ($q) {
            $q->withPivot(['product_count']);
        }])->where(function ($query) use ($id) {
            $query->where('user_id', Auth::id())
                ->where('id', $id);
        })->first();

        if (! $order) {
            throw new NotFoundHttpException("Order not found!");
        }

        $order->fill($request->only(['date_of_delivery', 'payment_method', 'contactless']))->save();

        event(new OnOrderUpdated($order));

        return response()->json($order);
    }

    public function cancel($id): JsonResponse
    {
        /**
         * @var $order Order
         */
        $order = Order::with(['user','address','products' => function ($q) {
            $q->withPivot(['product_count']);
        }])->where(function ($query) use ($id) {
            $query->where('user_id', Auth::id())
                ->where('id', $id);
        })->first();

        if (! $order) {
            throw new NotFoundHttpException("Order not found!");
        }

        if ($order->status == Order::STATUS_CANCELED) {
            throw new ConflictHttpException("Order already cancelled!");
        }

        $order->fill(['status' => Order::STATUS_CANCELED])->save();

        event(new OnOrderPaymentStatusChanged($order, $order->payment_status));

        event(new OnOrderUpdated($order));

        return response()->json($order);
    }

    public function pay(int $id, BillingServiceContract $billing)
    {
        $order = Order::with(['user','address','products' => function ($q) {
            $q->withPivot(['product_count']);
        }])->where(function ($query) use ($id) {
            $query->where('user_id', Auth::id())
                ->where('id', $id);
        })->first();

        if (! $order) {
            throw new NotFoundHttpException('Order not found!');
        } elseif ($order->price == 0) {
            throw new HttpException(424, 'Empty price');
        }

        try {
            $hash = hash('sha256', $order->id.time().config('app.key'));
            $order->fill([
                'payment_hash'   => $hash,
            ])->save();
            $response = $billing->registerOrder($order->id, $order->price, [
                'returnUrl' => route('order.pay_confirm', [$id, $hash]),
                'failUrl'   => route('order.pay_reject', [$id, $hash]),
            ]);


        } catch (AlreadyProgressOrderException $e) {
            throw new HttpException(424, $e->getMessage());
        } catch (RegisterOrderException $e) {
            throw new HttpException(500, $e->getMessage());
        }

        return response()->json($response);
    }

    public function pay_confirm(int $id, string $hash)
    {
        $order = Order::with(['user','address','products' => function ($q) {
            $q->withPivot(['product_count']);
        }])->where('id', $id)->first();

        if (! $order) {
            Log::error('Sberbank order not found', [
                'id' => $id,
                'hash' => $hash,
            ]);
            throw new NotFoundHttpException('Order not found!');
        }

        if ($hash != $order->payment_hash) {
            Log::error('Sberbank order found, but hash not match', [
                'id' => $id,
                'hash' => $hash,
                'order_hash' => $order->payment_hash,
            ]);
            throw new AccessDeniedHttpException('Access denied');
        }

        $order->fill([
            'payment_hash'   => null,
            'payment_status' => Order::STATUS_PAYED,
        ])->save();
        Log::info('Sberbank order was payed', [
            'id' => $id,
            'hash' => $hash,
            'order_hash' => $order->payment_hash,
        ]);

        event(new OnOrderPaymentStatusChanged($order, $order->payment_status));
        event(new OnOrderUpdated($order));

        return view('pages.success');
    }

    public function pay_reject(int $id, string $hash)
    {
        $order = Order::with(['user','address','products' => function ($q) {
            $q->withPivot(['product_count']);
        }])->where('id', $id)->first();

        if (! $order) {
            throw new NotFoundHttpException('Order not found!');
        }

        if ($hash != $order->payment_hash) {
            throw new AccessDeniedHttpException('Access denied');
        }

        $order->fill([
            'payment_hash'   => null,
            'payment_status' => Order::STATUS_CANCELED,
        ])->save();

        event(new OnOrderPaymentStatusChanged($order, $order->payment_status));
        event(new OnOrderUpdated($order));

        return view('pages.rejected');
    }

    public function lastOrder(int $address_id, string $status){

        $orderId = Order::where('address_id', $address_id)->where('status', $status)->max('id');
        $order = Order::find($orderId);
        return $order;
    }
}
