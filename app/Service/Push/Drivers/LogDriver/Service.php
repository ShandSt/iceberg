<?php

namespace App\Service\Push\Drivers\LogDriver;

use App\Service\Push\Contracts\PushMessageContract;
use App\Service\Push\Contracts\PushServiceContract;
use Illuminate\Support\Facades\Log;

class Service implements PushServiceContract
{
    /**
     * @param PushMessageContract $message
     * @param string $to
     * @return mixed
     */
    public function send(PushMessageContract $message, string $to): bool
    {
        Log::info("LogPushDriver",[
            'message' => $message->toArray(),
            'to' => $to,
        ]);

        return true;
    }

}