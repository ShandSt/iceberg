<?php

namespace App\Service\Sms\Driver;

use App\Service\Sms\Contracts\SmsServiceContract;
use App\Service\Sms\Exception\SendSmsException;
use Illuminate\Support\Facades\Log;
use Zelenin\SmsRu\Api;
use Zelenin\SmsRu\Auth\ApiIdAuth;
use Zelenin\SmsRu\Entity\Sms;
use Zelenin\SmsRu\Exception\Exception;

class SmsruDriver implements SmsServiceContract
{

    /**
     * @param string $phone
     * @param string $text
     * @param array $options
     * @throws SendSmsException
     * @return bool
     */
    public function sendSms(string $phone, string $text, array $options = []): bool
    {
        $response = $this->getClient()->smsSend(
            new Sms($phone, $text)
        );


        if (200 === (int)$response->code) {
            return true;
        }

        throw new SendSmsException(sprintf("Failed to send sms. Service response %s with code %s",
            $response->code,
            $response->getDescription()
        ));
    }


    private function getClient(): ?Api
    {
        if (null === env('SMS_RU_API_TOKEN')) {
            throw new Exception("sms ru api token not set.");
        }

        return new Api(new ApiIdAuth(env('SMS_RU_API_TOKEN')));
    }
}
