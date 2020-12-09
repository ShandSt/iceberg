<?php

namespace Tests\Feature;

use App\Events\Api\OnUserLogin;
use App\Events\Api\UserCreated;
use App\Listeners\Api\SendSmsConfirmatonOnUserLogin;
use App\Listeners\Api\SendSmsToUserAfterRegistration;
use App\Service\Sms\Contracts\SmsServiceContract;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    public function testLogin()
    {
        Event::fake();

        $user = factory(User::class)->create();

        $this->postJson(route('registration'), [
            'phone' => $user->phone,
            'os' => 'android',
            'token' => "AndroidDeviceToken",
            'device_id' => 'Iphone 8',
            'allow_push' => 1,
        ])->assertStatus(200);

        Event::assertDispatched(UserCreated::class, function (UserCreated $event) use ($user) {
            return $event->getUser()->id === $user->id;
        });
    }

    public function testSendSmsConfirmatonOnUserLogin()
    {
        $service = \Mockery::mock($this->app->make(SmsServiceContract::class));
        $service->shouldReceive('sendSms')
            ->once()
            ->withArgs([
                \Mockery::any(),
                \Mockery::any(),
            ])->andReturn(true);


        $event =  new UserCreated(
            $user = factory(User::class)->create(),
            $user->api_token,
            false
        );

        $listener = new SendSmsToUserAfterRegistration($service);
        $listener->handle($event);



        $this->assertEquals($event->getUser(), $user);
    }
}
