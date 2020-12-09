<?php

namespace App\Listeners\Api;

use App\Events\Api\OnOrderPaymentStatusChanged;
use App\Models\Order;
use App\Service\Push\Contracts\PushMessageContract;
use App\Service\Push\Contracts\PushServiceContract;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPushOnOrderStatusChanged implements ShouldQueue
{
    private $message;

    private $service;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(PushMessageContract $messageContract, PushServiceContract $serviceContract)
    {
        $this->message = $messageContract;
        $this->service = $serviceContract;
    }

    /**
     * Handle the event.
     *
     * @param  OnOrderPaymentStatusChanged $event
     * @return void
     */
    public function handle(OnOrderPaymentStatusChanged $event)
    {
        /** @var Order $order */
        $order = $event->order;

        $devices = $order->user->devices;

        if (count($devices) === 0) return;

        $this->message->setMessage(_("Your order payment status updated"));

        $this->message->setPayload([
            'order' => $event->order->toArray(),
            'newStatus' => $event->newStatus,
        ]);

        foreach ($devices as $device) {
            $this->message->setOs($device->os);
            $this->service->send($this->message, $device->token);
        }
    }
}
