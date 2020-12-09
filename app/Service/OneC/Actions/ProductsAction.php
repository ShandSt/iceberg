<?php

namespace App\Service\OneC\Actions;

use App\Service\OneC\Response\ProductsResponse;
use GuzzleHttp\Client;


class ProductsAction extends BaseAction
{

    public function get($guid = null, $page = null, $limit = 100): ProductsResponse
    {
        /**
         * @var $client Client
         */
        $client = $this->getClient(120);

        if (null === $guid) {
            $endpoint = sprintf("products/?page=%d&limit=%d", $page, $limit);
        } else {
            $endpoint = sprintf("products/%s", $guid);
        }

        return new ProductsResponse($client->get($endpoint));
    }

    public function getNewProducts($guid = null, $page = null, $limit = 100): ProductsResponse
    {
        /**
         * @var $client Client
         */
        $client = $this->getClient(120);

        if (null === $guid) {
            $endpoint = sprintf("products/GetProducts/?page=%d&limit=%d", $page, $limit);
        } else {
            $endpoint = sprintf("products/GetProducts/%s", $guid);
        }

        return new ProductsResponse($client->get($endpoint));
    }


}
