<?php

namespace Tests\Feature;

use App\Service\Billing\BillingResponse;
use App\Service\Billing\Contracts\BillingServiceContract;
use Tests\TestCase;

class BillingTest extends TestCase
{
    public function testPay()
    {
        $billing = $this->app->make(BillingServiceContract::class);

        $this->assertInstanceOf(
            BillingResponse::class,
            $billing->registerOrder(rand(0, 9999), rand(0, 9999))
        );
    }
}