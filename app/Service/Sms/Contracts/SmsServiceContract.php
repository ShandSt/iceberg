<?php

namespace App\Service\Sms\Contracts;

interface SmsServiceContract
{
    /**
     * @param string $phone
     * @param string $text
     * @param array $options
     * @return bool
     */
    public function sendSms(string $phone, string $text, array $options = []): bool;
}