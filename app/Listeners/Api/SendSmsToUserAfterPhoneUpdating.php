<?php

namespace App\Listeners\Api;

use App\Events\Api\OnUserPhoneUpdated;
use App\Models\ConfirmCode;
use App\Service\Sms\Contracts\SmsServiceContract;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendSmsToUserAfterPhoneUpdating
{
    private $sms;

    /**
     * Create the event listener.
     *
     * @param SmsServiceContract $sms
     */
    public function __construct(SmsServiceContract $sms)
    {
        $this->sms = $sms;
    }

    /**
     * Handle the event.
     *
     * @param  OnUserPhoneUpdated  $event
     * @return void
     */
    public function handle(OnUserPhoneUpdated $event)
    {
        $this->sms->sendSms($event->getUser()->phone, $code = random_int(1000, 9999));
        $confirm = ConfirmCode::firstOrNew(['user_id' => $event->getUser()->id]);
        $confirm->code = $code;
        $confirm->save();

    }
}
