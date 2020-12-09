<?php

namespace App\Service\Billing;

class BillingResponse
{
    public $orderId;

    public $formUrl;

    public $errorCode;

    public $errorMessage;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists(__CLASS__, $key)) {
                $this->$key = $value;
            }
        }
    }
}