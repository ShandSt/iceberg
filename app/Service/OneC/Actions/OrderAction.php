<?php

namespace App\Service\OneC\Actions;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;

class OrderAction extends BaseAction
{
    public function store(array $data): ResponseInterface
    {
        $response = $this->getClient()->post('orders', ['body' => \GuzzleHttp\json_encode($data)]);

        return $response;
    }

    public function update(string $id, array $data): ResponseInterface
    {
        $response = $this->getClient()->put(sprintf("orders/%s", $id), ['body' => \GuzzleHttp\json_encode($data)]);

        return $response;
    }

    public function findByUser(string $id): ResponseInterface
    {
        return $this->getClient()->get(sprintf("orders/FindByUser/%s", $id));
    }

    public function get(string $id): ResponseInterface
    {
        return $this->getClient()->get(sprintf("orders/%s", $id));
    }
}
