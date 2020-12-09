<?php

namespace App\Listeners\Api;

use App\Events\Api\OnOrderUpdated;
use App\Models\Order;
use App\Service\OneC\Actions\OrderAction;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderToOneCAfterUpdate
{
    /**
     * @var OrderAction
     */
    private $action;

    /**
     * Create the event listener.
     *
     * @param OrderAction $action
     */
    public function __construct(OrderAction $action)
    {
        $this->action = $action;
    }

    /**
     * Handle the event.
     *
     * @param  OnOrderUpdated  $event
     * @return void
     */
    public function handle(OnOrderUpdated $event)
    {
        $order = $event->getOrder();

        try {
            $response = $this->action->update($order->id, $order->load(['user', 'products', 'address'])->toArray());
        } catch (\Exception $e) {
            $this->addSyncFailFlag($order);

            return;
        }

        if ($response->getStatusCode() === 200) {
            $this->clearSyncFailFlag($order);
            try {
                $result = json_decode($response->getBody()->getContents());
                $order->guid = $result[0]->guid;
                $order->save();
            } catch (\Exception $e) {
                $this->addSyncFailFlag($order);
                \Log::error(__CLASS__, [
                    'order' => $order->id,
                    'response' => $response->getBody()->getContents(),
                ]);
            }
        } else {
            $this->addSyncFailFlag($order);
            \Log::error(__CLASS__, [
                'order' => $order->id,
                'status' => $response->getStatusCode(),
                'response' => $response->getBody()->getContents(),
            ]);
        }
    }

    private function addSyncFailFlag(Order $order)
    {
        $order->sync_failed_at = Carbon::now();
        $order->sync_attempts_count += 1;
        $order->save();
    }

    private function clearSyncFailFlag(Order $order)
    {
        $order->sync_failed_at = null;
        $order->sync_attempts_count = null;
        $order->save();
    }
}
