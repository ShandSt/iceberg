<?php

namespace App\Listeners\Api;

use App\Events\Api\OnOrderCreatedOneC;
use App\Service\Push\Contracts\PushMessageContract;
use App\Service\Push\Contracts\PushServiceContract;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendPushOnOrderDeliveryDatesUpdated implements ShouldQueue
{
    use InteractsWithQueue;

    private $message;

    private $service;

    /**
     * SendPushToUserOnBalanceUpdated constructor.
     * @param PushMessageContract $message
     * @param PushServiceContract $service
     */
    public function __construct(PushMessageContract $message, PushServiceContract $service)
    {
        $this->message = $message;
        $this->service = $service;
    }

    /**
     * Handle the event.
     *
     * @param  OnOrderCreatedOneC  $event
     * @return void
     */
    public function handle(OnOrderCreatedOneC $event)
    {
        $devices = $event->getOrder()->user->devices;

        if (count($devices) === 0) return;

        $this->message->setMessage(_("Dates of delivery updated"));

        $this->message->setPayload([
            'date_of_delivery_variants' => $event->getOrder()->date_of_delivery_variants,
        ]);


        foreach ($devices as $device) {
            $this->message->setOs($device->os);
            $this->service->send($this->message, $device->token);
        }
    }
}
