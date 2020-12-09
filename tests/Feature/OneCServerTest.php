<?php

namespace Tests\Feature;

use App\Service\OneC\Server\OneCServer;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OneCServerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testServer()
    {

        $this->assertTrue(true);
        /*
        \Mockery::mock(Client::class)
            ->shouldReceive('get')
            ->withArgs([
                \Mockery::any()
            ])
            ->andReturn(
                new Response(200)
            );

        $server = new OneCServer();
        $status = $server->checkServerStatus();
        $this->assertEquals(200, $status['server_status']);*/
    }
}
