<?php

namespace App\Listeners\Api;

use App\Events\Api\UserCreated;
use App\Models\ConfirmCode;
use App\Service\Sms\Contracts\SmsServiceContract;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendSmsToUserAfterRegistration implements ShouldQueue
{
    /**
     * @var SmsServiceContract
     */
    private $sms;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(SmsServiceContract $sms)
    {
        $this->sms = $sms;
    }

    /**
     * Handle the event.
     *
     * @param  UserCreated $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        if ($event->getUser()->phone === '+79990000000') {
            return;
        }

        if ($confirmCode = $event->getUser()->confirmCodes()->first()) {
            $code = $confirmCode->code;
        } else {
            $code = random_int(1000, 9999);
        }
        $this->sms->sendSms($event->getUser()->phone, $code);

        if (!$confirmCode) {
            $confirm = ConfirmCode::make(['user_id' => $event->getUser()->id]);
            $confirm->code = $code;
            $confirm->save();
        }
    }
}
