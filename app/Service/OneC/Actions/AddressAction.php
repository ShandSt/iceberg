<?php

namespace App\Service\OneC\Actions;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AddressAction extends BaseAction
{
    public function store(array $data): ResponseInterface
    {
        /**
         * @var $client Client
         */
        $client = $this->getClient();

        $response = $client->post('addresses', ['body' => \GuzzleHttp\json_encode($data)]);

        if ($response->getStatusCode() === 500) {
            throw new HttpException($response->getBody());
        }

        if (405 === $response->getStatusCode()) {
            throw new \InvalidArgumentException("Invalid input");
        }

        if (409 === $response->getStatusCode()) {
            throw new \Exception("Address already exists");
        }

        return $response;
    }

    public function findByUser(string $guid): ResponseInterface
    {
        /**
         * @var $client Client
         */
        $client = $this->getClient();

        $response = $client->get('addresses/findByUser?user='. $guid);

        if (400 === $response->getStatusCode()) {
            throw new \InvalidArgumentException("Invalid user id");
        }

        if (404 === $response->getStatusCode()) {
            throw new NotFoundHttpException("User not found");
        }

        return $response;
    }

    public function delete(string $guid): ResponseInterface
    {
        /**
         * @var $client Client
         */
        $client = $this->getClient();

        $response = $client->delete(sprintf("addresses/%s", $guid));

        if (400 === $response->getStatusCode()) {
            throw new \InvalidArgumentException("Invalid id supplied");
        }

        if (404 === $response->getStatusCode()) {
            throw new NotFoundHttpException("Address not found");
        }

        return $response;
    }

    public function show(string $guid): ResponseInterface
    {
        /**
         * @var $client Client
         */
        $client = $this->getClient();

        $response = $client->get(sprintf("addresses/%s", $guid));

        if (400 === $response->getStatusCode()) {
            throw new \InvalidArgumentException("Invalid id supplied");
        }

        if (404 === $response->getStatusCode()) {
            throw new NotFoundHttpException("Address not found");
        }

        return $response;
    }

    public function update(string $guid, array $data): ResponseInterface
    {
        /**
         * @var $client Client
         */
        $client = $this->getClient();

        $response = $client->put(sprintf("addresses/%s", $guid), $data);

        if (400 === $response->getStatusCode()) {
            throw new \InvalidArgumentException("Invalid id supplied");
        }

        if (404 === $response->getStatusCode()) {
            throw new NotFoundHttpException("Address not found");
        }

        if (405 === $response->getStatusCode()) {
            throw new \InvalidArgumentException("Validation exception");
        }

        return $response;
    }

    public function connectToUser(string $address_id, string $user_id)// ResponseInterface
    {
        /**
         * @var $client Client
         */
        $client = $this->getClient();

        $data = [
            'user' => $user_id,
            'address' => $address_id,
        ];
/*
        $response = $client->post('user-address', $data);

        if (405 === $response->getStatusCode()) {
            throw new \InvalidArgumentException("Invalid id input");
        }

        if (409 === $response->getStatusCode()) {
            throw new NotFoundHttpException("Link already exists");
        }

        return $response;
*/
    }

    public function getCities(): ResponseInterface
    {
        /**
         * @var $client Client
         */
        $client = $this->getClient();

        $response = $client->get('addresses/Cities');

        return $response;
    }

    public function getStreets(): ResponseInterface
    {
        /**
         * @var $client Client
         */
        $client = $this->getClient();

        $response = $client->get('addresses/Streets');

        return $response;
    }

}
