<?php

namespace App\Service\OneC\Response;

use Psr\Http\Message\ResponseInterface;

class ProductsResponse
{
    private $response;

    private $p_count;

    private $p_page;

    private $p_limit;

    private $p_pages;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
        $this->pager();
    }

    public function total(): int
    {
        return $this->p_pages;
    }


    private function prepareHeader(string $name)
    {
        if (isset($this->response->getHeader($name)[0])) {
            return $this->response->getHeader($name)[0];
        }

        return false;
    }


    private function pager()
    {
        $this->p_count = $this->prepareHeader('Pagination-Count');
        $this->p_page = $this->prepareHeader('Pagination-Page');
        $this->p_limit = $this->prepareHeader('Pagination-Limit');

        if (false !== $this->p_count || false !== $this->p_limit) {
            $this->p_pages = $this->p_count / $this->p_limit;
        }
    }

    public function getData(): array
    {
        return json_decode($this->response->getBody()->getContents(), true);
    }

    public function hasNextPage(): bool
    {
        if ($this->p_page < $this->p_pages) {
            return true;
        }
        return false;
    }

    public function hasPrevPage()
    {
        if ($this->p_page <= 1) {
            return false;
        }
        return true;
    }
}