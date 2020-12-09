<?php

namespace App\Service\Push\Drivers\LaravelPushNotificationDriver;

use App\Service\Push\Contracts\PushMessageContract;
use App\Service\Push\Contracts\PushServiceContract;
use App\Service\Push\Exception\DriverNotFoundException;
use Davibennun\LaravelPushNotification\Facades\PushNotification;

class Service implements PushServiceContract
{
    public function send(PushMessageContract $message, string $to): bool
    {
        $config = config('services.push.os_to_app');

        $app = isset($config[$message->getOs()]) ? $config[$message->getOs()] : null;

        if (null === $app) {
            throw new DriverNotFoundException(sprintf("Driver for this os not found"));
        }

        PushNotification::app($app)
            ->to($to)
            ->send($this->prepareMessage($message));

        return true;
    }

    private function prepareMessage(PushMessageContract $message)
    {
        return PushNotification::Message($message->getMessage(),$message->getPayload());
    }
}
