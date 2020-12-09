<?php

namespace App\Listeners\Api;

use App\Events\Api\OnOrderCreated;
use App\Models\Order;
use App\Service\OneC\Actions\OrderAction;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendOrderToOneCAfterCreate
{
    /**
     * @var OrderAction
     */
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
     * @param  OnOrderCreated $event
     * @return void
     */
    public function handle(OnOrderCreated $event)
    {
        try {
            Log::info('Get order delivery time #'.$event->getOrder()->id.': '.\GuzzleHttp\json_encode($event->getOrder()->fresh()->load(['user', 'products', 'address'])->toArray()));
            $response = $this->action->store($event->getOrder()->fresh()->load(['user', 'products', 'address'])->toArray());
        } catch (\Exception $e) {
            Log::error('Failing get order delivery time #'.$event->getOrder()->id);
            $this->setDefaults($event->getOrder(), $e->getMessage());
            return;
        }

        $json = $response->getBody();
        try {
            if ($response->getStatusCode() === 200) {
                $order = array_get(json_decode($json), 0);
                $event->getOrder()->fill([
                    'guid' => $order->guid,
                    'date_of_delivery_variants' => $order->date_of_delivery_variants,
                    'price' => $order->price,
                    'status' => $order->status,
                    'popup_message' => $order->popup_message ?? null,
                ])->save();
            } else {
                Log::error('Failing set order delivery time #'.$event->getOrder()->id.' from 1C: '.$json);
                $this->setDefaults($event->getOrder(), 'Response status is '.$response->getStatusCode());
            }
        } catch (\Exception $e) {
            Log::error('Failing set order delivery time #'.$event->getOrder()->id.' from 1C: '.$json);
            $this->setDefaults($event->getOrder(), $e->getMessage());
        }
    }

    private function setDefaults(Order $order, string $error = ''): void
    {
        $order->fill(
            [
                'guid' => '00000000-0000-0000-0000-000000000000',
                'date_of_delivery_variants' => $this->getDeliveryDates(),
            ]
        )->save();

        Log::error("LoadOrderToOnecServer: Error", [
            'order' => $order->id,
            'response' => $error,
        ]);
    }

    private function getDeliveryDates(): array
    {
        if (in_array(today()->dayOfWeek, [5, 6, 0])) {
            return [["Name" => "C 8 до 22 часов ", "date" => Carbon::today()->nextWeekday()->format('Y-m-d')."T08:00:00"]];
        }

        return [["Name" => "C 8 до 22 часов ", "date" => Carbon::tomorrow()->format('Y-m-d')."T08:00:00"]];
    }
}
