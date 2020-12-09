<?php

namespace App\Service\Billing\Drivers;

use App\Service\Billing\BillingResponse;
use App\Service\Billing\Contracts\BillingServiceContract;
use Illuminate\Support\Facades\Log;

class BillingLogDriver implements BillingServiceContract
{

    /**
     * @param int   $orderId
     * @param int   $amount
     * @param array $options
     * @return BillingResponse
     */
    public function registerOrder(int $orderId, int $amount, array $options = []): BillingResponse
    {

        $faker = \Faker\Factory::create();

        $response = new BillingResponse([
            'orderId'      => $faker->uuid,
            'formUrl'      => $faker->url,
            'errorCode'    => 0,
            'errorMessage' => null,
        ]);

        return $response;
    }
}
