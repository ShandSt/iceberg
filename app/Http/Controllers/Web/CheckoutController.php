<?php

namespace App\Http\Controllers\Web;

use App\Events\Api\OnOrderPaymentStatusChanged;
use App\Events\Api\OnOrderUpdated;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\City;
use App\Models\Order;
use App\Models\Street;
use App\Service\Billing\Contracts\BillingServiceContract;
use App\Service\SiteOrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Class CheckoutController
 */
class CheckoutController extends Controller
{
    /**
     * @param Cart $cart
     * @return \Illuminate\Contracts\View\Factory|RedirectResponse|View
     */
    public function cart(Cart $cart)
    {
        if ($cart->items()->count() === 0) {
            return redirect()->route('catalog');
        }

        $streets = City::with('streets')->get()->map(
            function (City $city) {
                return [
                    'id' => $city->id,
                    'streets' => $city->streets->map(
                        function (Street $street) {
                            return [
                                'id' => $street->id,
                                'name' => $street->street,
                            ];
                        }
                    ),
                ];
            }
        );

        $customer = json_decode(request()->cookie('customer', null));

        return view(
            'checkout.cart',
            [
                'streets' => $streets,
                'customer' => $customer,
            ]
        );
    }

    public function confirmation(Request $request, Cart $cart, SiteOrderService $service)
    {
        if (!$cart->isAllowProceedCheckout()) {
            return redirect()->route('cart');
        }

        $this->validate(
            $request,
            [
                'accept_terms' => 'accepted',
                'name' => 'required',
                'phone' => 'required|regex:/\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}/',
                'city_id' => 'required|exists:cities,id',
                'street_id' => 'required_if:street_manual,"0"|exists:streets,id',
                'street' => 'required_if:street_manual,"1"',
                'street_manual' => 'required|boolean',
                'house' => 'required',
                'delivery_sms' => 'required|boolean',
                'back_call' => 'required|boolean',
                'intercom_does_not_work' => 'required|boolean',
                'contactless' => 'required|boolean',
                'comment' => 'nullable|max:150',
            ]
        );

        if ($request->input('street_manual')) {
            unset($request['street_id']);
        } else {
            unset($request['street']);
        }

        $data = $request->only(
            [
                'name',
                'phone',
                'city_id',
                'street_id',
                'street',
                'street_manual',
                'house',
                'apartment',
                'floor',
                'entrance',
                'delivery_sms',
                'back_call',
                'intercom_does_not_work',
                'contactless',
                'comment',
            ]
        );

        $cookie = cookie('customer', json_encode($data), 60*24*365);

        $order = $service->create($request->all(), $cart);

        $hash = md5($order->id.'-'.$order->created_at);
        $request->session()->put($hash, $order->id);

        return redirect()
            ->route('order-delivery', $hash)
            ->cookie($cookie);
    }

    public function deliveryTime(string $hash): View
    {
        $orderId = request()->session()->get($hash);
        if (!$orderId) {
            return abort(404);
        }
        $order = Order::findOrFail($orderId);

        return view('checkout.order-confirmation', ['order' => $order, 'hash' => $hash]);
    }

    /**
     * @param SiteOrderService $service
     * @param Request $request
     * @return RedirectResponse|void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function checkout(SiteOrderService $service, Request $request)
    {
        $this->validate(
            $request,
            [
                'hash' => 'required',
                'payment_method' => 'required|in:Card,Cash,Bill',
                'date_of_delivery' => 'required|string',
            ]
        );

        $orderId = request()->session()->get($request->input('hash'));
        if (!$orderId) {
            return abort(404);
        }
        $order = Order::findOrFail($orderId);

        $request->session()->remove('cart');
        $request->session()->remove('hash');
        $request->session()->put('order', $order->id);

        $service->confirm(
            $order,
            $request->input('payment_method'),
            $request->input('date_of_delivery')
        );

        return redirect()->route('order');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|RedirectResponse|View
     */
    public function order(Request $request)
    {
        $orderId = $request->session()->get('order');

        if (!$orderId) {
            return redirect()->route('catalog');
        }

        $order = Order::find($orderId);

        if (!$order) {
            return redirect()->route('catalog');
        }

        if ($order->payment_method === 'Card') {
            return redirect()
                ->route('pay', $order);
        }

        return view('checkout.order', ['order' => $order]);
    }

    public function pay(BillingServiceContract $billing, int $orderId)
    {
        $order = Order::findOrFail($orderId);

        try {
            $hash = hash('sha256', $order->id.time().config('app.key'));
            $order->payment_hash = $hash;
            $order->save();
            $response = $billing->registerOrder(
                $order->id,
                $order->price,
                [
                    'returnUrl' => route('pay-confirm', $hash),
                    'failUrl' => route('pay-reject', $hash),
                ]
            );
            request()->session()->remove('order');
            Log::info('Order '.$orderId.' pay url: '.$response->formUrl);

            return redirect($response->formUrl);
        } catch (\Exception $e) {
            Log::error('Order '.$orderId.' pay error: '.$e->getMessage());

            return redirect()->route('catalog');
        }
    }

    public function pay_confirm(string $hash)
    {
        $order = Order::where('payment_hash', $hash)->first();

        if (!$order) {
            Log::warning('Trying confirm order with hash '.$hash);

            return redirect()->route('catalog');
        }

        $order->payment_hash = null;
        $order->payment_status = Order::STATUS_PAYED;
        $order->save();

        Log::info('Confirm order '.$order->id.' with hash '.$hash);

        $order->load(
            [
                'user',
                'address',
                'products' => function ($q) {
                    $q->withPivot(['product_count']);
                },
            ]
        );

        event(new OnOrderPaymentStatusChanged($order, $order->payment_status));
        event(new OnOrderUpdated($order));

        return view('checkout.payed', ['order' => $order]);
    }

    public function pay_reject(string $hash)
    {
        $order = Order::where('payment_hash', $hash)->first();

        if (!$order) {
            Log::warning('Trying reject order with hash '.$hash);

            return redirect()->route('catalog');
        }

        $order->payment_hash = null;
        $order->payment_status = Order::STATUS_CANCELED;
        $order->save();

        Log::info('Reject order '.$order->id.' with hash '.$hash);

        $order->load(
            [
                'user',
                'address',
                'products' => function ($q) {
                    $q->withPivot(['product_count']);
                },
            ]
        );

        event(new OnOrderPaymentStatusChanged($order, $order->payment_status));
        event(new OnOrderUpdated($order));

        return redirect()->route('catalog');
    }
}
