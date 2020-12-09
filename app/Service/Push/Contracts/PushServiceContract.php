<?php

namespace App\Service\Push\Contracts;

interface PushServiceContract
{
    public function send(PushMessageContract $message, string $to): bool;
}