<?php

namespace App\Service\Push\Contracts;

interface PushMessageContract
{
    public function setMessage(string $message): PushMessageContract;

    public function setPayload(array $payload): PushMessageContract;

    public function getMessage(): string;

    public function getPayload(): array;

    public function setOs(string $os): PushMessageContract;

    public function getOs(): string;

    public function toArray(): array;
}