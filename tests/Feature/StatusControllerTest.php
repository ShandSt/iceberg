<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StatusControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStatus()
    {
        Artisan::call('1c:server:status');

        $this->getJson(route('status'))
            ->assertStatus(200);
    }
}
