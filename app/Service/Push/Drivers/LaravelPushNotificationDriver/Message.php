<?php

namespace App\Service\Push\Drivers\LaravelPushNotificationDriver;

use App\Service\Push\Contracts\PushMessageContract;

class Message implements PushMessageContract
{
    private $message;

    private $payload;

    private $os;

    /**
     * @param string $message
     * @return PushMessageContract
     */
    public function setMessage(string $message): PushMessageContract
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @param array $payload
     * @return PushMessageContract
     */
    public function setPayload(array $payload): PushMessageContract
    {
        $this->payload = $payload;

        return $this;
    }


    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $os
     * @return PushMessageContract
     */
    public function setOs(string $os): PushMessageContract
    {
        $this->os = $os;

        return $this;
    }

    /**
     * @return string
     */
    public function getOs(): string
    {
        return $this->os;
    }


    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'message' => $this->getMessage(),
            'payload' => $this->getPayload(),
            'os' => $this->getOs(),
        ];
    }

}