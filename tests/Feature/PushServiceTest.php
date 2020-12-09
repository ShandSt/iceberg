<?php

namespace Tests\Feature;

use App\Service\Push\Contracts\PushMessageContract;
use App\Service\Push\Contracts\PushServiceContract;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PushServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSendPush()
    {
        /**
         * @var $service PushServiceContract
         * @var $message PushMessageContract
         */
        $service = $this->app->make(PushServiceContract::class);
        $message = $this->app->make(PushMessageContract::class);
        $message->setMessage('Hello world');
        $message->setPayload([
            'data' => [
                'Ivan',
                'Petr',
            ]
        ]);
        $message->setOs('android');

        \Mockery::mock(PushServiceContract::class)
            ->shouldReceive('send')
            ->withArgs([
                \Mockery::mock(PushMessageContract::class),
                \Mockery::any(),
            ]);

        $this->assertTrue($service->send($message, 'test token'));

    }
}
