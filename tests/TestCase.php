<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function apiHeaders(string $token, $header_name = 'Authorization')
    {
        return [
            $header_name => sprintf("Bearer %s", $token),
            'Accept' => 'application/json',
        ];
    }



}
