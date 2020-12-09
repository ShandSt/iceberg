<?php

namespace App\Service\Sms\Driver;

use App\Service\Sms\Contracts\SmsServiceContract;
use Illuminate\Support\Facades\Log;

class SmsLogDriver implements SmsServiceContract
{
    /**
     * @param string $phone
     * @param string $text
     * @param array $options
     * @return bool
     */
    public function sendSms(string $phone, string $text, array $options = []): bool
    {
        Log::info('send sms log driver', func_get_args());

        return true;
    }

}
