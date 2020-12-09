<?php

namespace Tests\Feature;

use App\Events\Api\UserCreated;
use App\Listeners\Api\SendSmsToUserAfterRegistration;
use App\Service\Sms\Contracts\SmsServiceContract;
use App\User;
use Illuminate\Contracts\Logging\Log;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery as m;

class SendSmsToUserAfterRegistrationTest extends TestCase
{

    private $user;

    private $token;

    private $sms_service;

    public function setUp()
    {
        parent::setUp();

        $this->user = m::mock(User::class);
        $this->token = str_random(120);
        $this->sms_service = m::mock($this->app->make(SmsServiceContract::class));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testHandle()
    {
        \Illuminate\Support\Facades\Log::shouldReceive('info')
            ->withArgs([
                m::any(),
                m::any(),
            ])
            ->andReturnUndefined();


        $user = new User();
        $user->phone = '+380675906752';
        $user->id = 1;

        $listener = new SendSmsToUserAfterRegistration($this->sms_service);
        $listener->handle(
            $e = new UserCreated($user, $this->token, true)
        );

        $this->assertEquals($user, $e->getUser());
    }
}
