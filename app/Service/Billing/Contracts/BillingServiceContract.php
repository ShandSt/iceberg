<?php

namespace App\Service\Billing\Contracts;

use App\Service\Billing\BillingResponse;

interface BillingServiceContract
{
    /**
     * @param int   $orderId
     * @param int   $amount
     * @param array $options
     * @return BillingResponse
     */
    public function registerOrder(int $orderId, int $amount, array $options = []): BillingResponse;

    public function checkOrder(int $orderId): bool;
}