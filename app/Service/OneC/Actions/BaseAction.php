<?php

namespace App\Service\OneC\Actions;

use App\Service\OneC\Contracts\OneCClientContract;
use GuzzleHttp\Client;

class BaseAction
{
    /**
     * @param int $readTimeout
     * @return Client
     */
    public function getClient(int $readTimeout = 4)
    {
        return app()->make(OneCClientContract::class)->getClient($readTimeout);
    }
}
