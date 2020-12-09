<?php

namespace App\Service\OneC\Client;

use App\Service\OneC\Contracts\OneCClientContract;
use GuzzleHttp\Client;

class Base implements OneCClientContract
{
    /**
     * @param  int  $readTimeout
     * @return Client
     */
    public function getClient(int $readTimeout = 6)
    {
        $client = new Client(
            [
                'base_uri' => config('onec.endpoint'),
                'http_errors' => false,
                'connect_timeout' => 2,
                'read_timeout' => $readTimeout,
                'timeout' => $readTimeout,
            ]
        );


        return $client;
    }
}
