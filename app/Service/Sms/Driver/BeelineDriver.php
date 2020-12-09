<?php

namespace App\Service\Sms\Driver;

use App\Service\Sms\Contracts\SmsServiceContract;
use App\Service\Sms\Exception\SendSmsException;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

/**
 * Driver for sms sms to beeline
 */
class BeelineDriver implements SmsServiceContract
{
    /**
     * Send sms
     *
     * @param  string $phone
     * @param  string $text
     * @param  array  $options
     * @return bool
     */
    public function sendSms(string $phone, string $text, array $options = []): bool
    {
        $msg = iconv("utf-8", "windows-1251", $text);
        try {
            $client = new Client([
                'base_uri' => 'https://beeline.amega-inform.ru',
                'timeout'  => 1.0,
            ]);
            $client->request('POST', '/sendsms/', [
                'form_params' => [
                    'user' => env('BEELINE_USER' ,'EXT_Aisberg1'),
                    'pass' => env('BEELINE_PASSWORD', '9097882178'),
                    'CLIENTADR' => '127.0.0.1',
                    'HTTP_ACCEPT_LANGUAGE' => 'en,ru;q=0.8,fr;q=0.6,es;q=0.4,de;q=0.2,nl;q=0.2,it;q=0.2,en-US;q=0.2',
                    'action' => 'post_sms',
                    'message' => $msg,
                    'target' => $phone
                ]
            ]);
        } catch(\Exception $e) {
            Log::error($e->getMessage());            
        }

        return true;
    }

}
