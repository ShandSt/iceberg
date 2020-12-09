<?php

namespace App\Listeners\Api;

use App\Events\Api\OnUserLogin;
use App\Service\Sms\Contracts\SmsServiceContract;
use App\Service\Sms\Exception\SendSmsException;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\ConfirmCode;

class SendSmsConfirmatonOnUserLogin implements ShouldQueue
{

    private $service;

    /**
     * SendSmsConfirmatonOnUserLogin constructor.
     * @param SmsServiceContract $service
     * @return void
     */
    public function __construct(SmsServiceContract $service)
    {
        $this->service = $service;
    }

    /**
     * Handle the event.
     *
     * @param  OnUserLogin  $event
     * @return void
     */
    public function handle(OnUserLogin $event)
    {
        $this->service->sendSms(
            $event->getUser()->phone,
            $code = random_int(1000, 9999)
        );
        $confirm = ConfirmCode::firstOrNew(['user_id' => $event->getUser()->id]);
        $confirm->code = $code;
        $confirm->save();

    }
}
