<?php

namespace App\Listeners\Api;

use App\Events\Api\OnAppLaunch;
use App\Models\Order;
use App\Models\Product;
use App\Service\OneC\Actions\OrderAction;
use Illuminate\Http\Response;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GetUserOrdersFromOneC
{
    private $action;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(OrderAction $action)
    {
        $this->action = $action;
    }

    /**
     * Handle the event.
     *
     * @param  OnAppLaunch  $event
     * @return void
     */
    public function handle(OnAppLaunch $event)
    {
        $response = $this->action->findByUser($event->getUser()->id);

        if ($response->getStatusCode() === Response::HTTP_OK) {
            $orders = \GuzzleHttp\json_decode($response->getBody());

            foreach ($orders as $order) {
                if (empty($order->id) and is_null(Order::whereGuid($order->guid)->first())) {
                    if (empty($order->address->id)) {
                        continue;
                    }
                    $newOrder = new Order((array) $order);
                    //$newOrder->litrs = $newOrder->bottles * Order::ONE_BOTTLE_LITRS;
                    $newOrder->user_id = $event->getUser()->id;
                    $newOrder->created_at = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i:s', $order->created_at);
                    $newOrder->address_id = str_replace(' ', '', $order->address->id);
                    $newOrder->save();

                    foreach($order->products as $orderProduct) {
                        $product = Product::whereGuid($orderProduct->guid)->first();
                        if ($product) {
                            $newOrder->products()->attach($product->id, [
                                'product_count' => $orderProduct->pivot->product_count,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
