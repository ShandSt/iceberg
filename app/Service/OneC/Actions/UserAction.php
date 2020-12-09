<?php

namespace App\Service\OneC\Actions;

use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserAction extends BaseAction
{
    public function addUser(array $data): bool
    {
        /**
         * @var $client Client
         */
        $client = $this->getClient();

        $response = $client->post('users', ['body' => \GuzzleHttp\json_encode($data)]);

        if ($response->getStatusCode() === 500) {
            throw new HttpException(500, $response->getBody());
        }

        if ($response->getStatusCode() === 405) {
            throw new HttpException(405, "1C response code 405 (Invalid input)");
        }

        if ($response->getStatusCode() === 409) {
            throw new HttpException(409, "User already exists");
        }

        $responseUser = array_get(\GuzzleHttp\json_decode($response->getBody()), 0);

        \App\User::find($data['id'])->fill(['guid' => $responseUser->guid])->save();

        return true;
    }

    public function findByPhone(string $phone): Response
    {
        $client = $this->getClient();

        /**
         * @var $response Response
         */
        $response = $client->get("users/findByPhone?phone={$phone}");

        if ($response->getStatusCode() === 400) {
            throw new \InvalidArgumentException("Invalid phone number");
        }

        if ($response->getStatusCode() === 404) {
            throw new NotFoundHttpException("User not found in 1c system");
        }

        return $response;
    }


    public function getUser(string $id): Response
    {
        $client = $this->getClient();

        /**
         * @var $response Response
         */

        $response = $client->get("users/{$id}");

        if ($response->getStatusCode() === 404) {
            throw new NotFoundHttpException("User not found");
        }

        if ($response->getStatusCode() === 400) {
            throw new \InvalidArgumentException("Invalid user id");
        }

        return $response;
    }


    public function updateUser(string $id, array $data)
    {
        /**
         * @var $client Client
         */
        $client = $this->getClient();

        /**
         * @var $response ResponseInterface
         */
        $response = $client->put("users/{$id}", ['body' => \GuzzleHttp\json_encode($data)]);

        if ($response->getStatusCode() === 500) {
            throw new HttpException($response->getBody());
        }

        if ($response->getStatusCode() === 400) {
            throw new \InvalidArgumentException("Invalid user id");
        }

        if ($response->getStatusCode() ===  404) {
            $response = $this->addUser($data);
//            throw new NotFoundHttpException("User not found");
        }

        if ($response->getStatusCode() === 405) {
            throw new \InvalidArgumentException("Bad data. Validator error");
        }

        return true;
    }


    public function getBottles(string $user_id)
    {
        $client = $this->getClient();

        /**
         * @var $response Response
         */
        $response = $client->get("bottles/{$user_id}");

        if (400 === $response->getStatusCode()) {
            throw new \InvalidArgumentException("Invalid user id");
        }

        if (404 === $response->getStatusCode()) {
            throw new NotFoundHttpException("User not found");
        }

        return $response;
    }

    public function setBottles(string $user_id, array $data)
    {
        $client = $this->getClient();

        /**
         * @var $response Response
         */
        $response = $client->post("bottles/{$user_id}", ['body' => \GuzzleHttp\json_encode($data)]);

        if (400 === $response->getStatusCode()) {
            throw new \InvalidArgumentException("Invalid user id");
        }

        if (404 === $response->getStatusCode()) {
            throw new NotFoundHttpException("User not found");
        }

        if (405 === $response->getStatusCode()) {
            throw new \Exception("Validation exception");
        }

        return $response;
    }

    public function setConsumption(float $consumption, string $id): ResponseInterface
    {
        $client = $this->getClient();

        $response = $client->post(sprintf("user/%d/consumption", $id), ['body' => \GuzzleHttp\json_encode([
            'consumption' => $consumption,
        ])]);

        return $response;
    }

    public function getConsumption(string $id): ResponseInterface
    {
        return $this->getClient()->get(sprintf("user/%d/consumption", $id));
    }
}