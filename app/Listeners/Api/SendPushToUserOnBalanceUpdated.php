<?php

namespace App\Listeners\Api;

use App\Events\Api\OnUserBalanceChanged;
use App\Service\Push\Contracts\PushMessageContract;
use App\Service\Push\Contracts\PushServiceContract;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendPushToUserOnBalanceUpdated implements ShouldQueue
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
     * @param  OnUserBalanceChanged $event
     * @return void
     */
    public function handle(OnUserBalanceChanged $event)
    {
        $devices = $event->getUser()->devices;

        if (count($devices) === 0) return;

        $this->message->setMessage(_("Your balance updated"));

        $this->message->setPayload([
            'user' => $event->getUser()->toArray(),
        ]);

        foreach ($devices as $device) {
            $this->message->setOs($device->os);
            $this->service->send($this->message, $device->token);
        }
    }
}
