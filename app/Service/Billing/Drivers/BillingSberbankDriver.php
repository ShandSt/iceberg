<?php

namespace App\Service\Billing\Drivers;

use App\Service\Billing\BillingResponse;
use App\Service\Billing\Contracts\BillingServiceContract;
use App\Service\Billing\Exception\AlreadyProgressOrderException;
use App\Service\Billing\Exception\RegisterOrderException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class BillingSberbankDriver implements BillingServiceContract
{
//    const BILLING_HOST = 'https://3dsec.sberbank.ru';
    const BILLING_HOST = 'https://securepayments.sberbank.ru';

    /**
     * @param int   $orderId
     * @param int   $amount
     * @param array $options
     * @return BillingResponse
     * @throws RegisterOrderException
     */
    public function registerOrder(int $orderId, int $amount, array $options = []): BillingResponse
    {
        $config = config('sberbank');
        $client = new Client();
        
        $response = $client->request('GET', self::BILLING_HOST . '/payment/rest/register.do?'. http_build_query([
            'userName'    => $config['api_login'],
            'password'    => $config['password'],
            'orderNumber' => $orderId,
            'amount'      => $amount * 100,
            'returnUrl'   => array_get($options, 'returnUrl'),
            'failUrl'     => array_get($options, 'failUrl'),
        ]));

        $response = new BillingResponse((array) json_decode($response->getBody()));

        if ($response->errorCode > 0 or empty($response->orderId)) {
            Log::error('Error register order Sberbank', [
                'orderId'      => $orderId,
                'amount'       => $amount,
                'errorCode'    => $response->errorCode,
                'errorMessage' => $response->errorMessage,
            ]);
            if ($response->errorCode == 1) {
                throw new AlreadyProgressOrderException($response->errorMessage);
            } else {
                throw new RegisterOrderException($response->errorMessage);
            }
        }

        return $response;
    }

    /**
     * @param int $orderId
     * @return bool
     */
    public function checkOrder(int $orderId): bool
    {
        $config = config('sberbank');
        $client = new Client();

        $response = $client->request(
            'GET',
            self::BILLING_HOST.'/payment/rest/getOrderStatusExtended.do?'.http_build_query(
                [
                    'userName' => $config['api_login'],
                    'password' => $config['password'],
                    'orderNumber' => $orderId,
                ]
            )
        );

        $response = json_decode($response->getBody());

        if (isset($response->orderStatus) && $response->orderStatus === 2) {
            return true;
        }

        return false;
    }
}
