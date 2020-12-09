<?php

namespace App\Listeners\Api;

use App\Events\Api\OnAlmostOutOfWater;
use App\Service\Push\Contracts\PushMessageContract;
use App\Service\Push\Contracts\PushServiceContract;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPushOnAlmostOutOfWater implements ShouldQueue
{
    use InteractsWithQueue;

    private $message;

    private $service;

    /**
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
     * @param  OnAlmostOutOfWater  $event
     * @return void
     */
    public function handle(OnAlmostOutOfWater $event)
    {
        $devices = $event->getUser()->devices;

        $payload = [
            'aps' => [
                'alert' => [
                    'title' => "У Вас заканчивается вода.",
                    'body' => "Не забудьте сделать заказ.🚚",
                ],
                'type' => 'waterNotification',
                'sound' => 'default',
            ],
        ];

        if (count($devices) === 0) return;

        $this->message->setMessage('У Вас заканчивается вода. Не забудьте сделать заказ.🚚');
        $this->message->setPayload($payload);

        foreach ($devices as $device) {
            if ($device->token == '12345') {
                continue;
            }
            $this->message->setOs($device->os);
            $this->service->send($this->message, $device->token);
        }
    }
}
