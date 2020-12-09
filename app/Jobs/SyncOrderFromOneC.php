<?php

namespace App\Jobs;

use App\Events\Api\OnOrderPaymentStatusChanged;
use App\Models\Order;
use App\Service\OneC\Actions\OrderAction;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SyncOrderFromOneC implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var $order Order
     */
    private $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(OrderAction $action)
    {
        $find = $action->get($this->order->id);

        if ($find->getStatusCode() === 404) {
            $this->addOrder($action);
        } else {
            $body = $find->getBody()->getContents();
            $body = json_decode($body, true);

            if(!$body) return false;

            if ($this->order->status != $body[0]['status']) {
                $this->order->status = $body[0]['status'];
                $this->order->save();
                event(new OnOrderPaymentStatusChanged($this->order, $body[0]['status']));
            }
            if ($this->order->price != $body[0]['price']) {
                $this->order->price = $body[0]['price'];
                $this->order->save();
            }
        }
    }

    private function addOrder(OrderAction $action)
    {
        $this->order->load(['products' => function ($q) {
            $q->withPivot('product_count');
        }]);

        $result = $action->store($this->order->toArray());

        if ($result->getStatusCode() !== 200) {
            Log::error('Can not add order to 1C',[
                'order' => $this->order->id,
                'response' => $result->getBody()->getContents(),
            ]);
        }
    }

}
