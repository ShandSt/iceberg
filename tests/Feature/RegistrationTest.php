<?php

namespace Tests\Feature;

use App\Events\Api\UserCreated;
use App\Models\ConfirmCode;
use App\Service\Sms\Contracts\SmsServiceContract;
use App\User;
use Illuminate\Foundation\Testing\Concerns\InteractsWithAuthentication;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegistrationTest extends TestCase
{

    use DatabaseTransactions;

    public function testRegistration()
    {

        Event::fake();

        $phone = '+380675906752';

        $this->postJson(route('registration'), [
            'phone' => $phone,
            'os' => 'android',
            'token' => "AndroidDeviceToken",
            'device_id' => 'Iphone 8',
            'allow_push' => 1,
        ])->assertStatus(200)->assertJsonStructure([
            'status',
            'data' => [
                'user',
                'token',
            ],
        ]);

        $user = User::wherePhone($phone)->first();

        $this->assertNotFalse($user);
        $this->assertEquals($phone, $user->phone);

        Event::assertDispatched(UserCreated::class, function (UserCreated $e) use ($phone) {
            return $e->getUser()->phone === $phone && is_string($e->getToken());
        });
    }

    public function testConfirm()
    {
        $user = factory(User::class)->create();

        $code = factory(ConfirmCode::class)->create([
            'user_id' => $user->id,
        ]);

        $this->postJson(route('registration.code'), [
            'code' => $code->code,
            'user_id' => $user->id,
        ], $this->apiHeaders($user->api_token))->assertStatus(200)
            ->assertJson([
                'status' => Response::HTTP_OK,
                'data' => []
            ]);

        $this->assertNull(
            ConfirmCode::find($code->id)
        );
    }

    public function testBadConfirmCode()
    {
        $user = factory(User::class)->create();

        factory(ConfirmCode::class)->create([
            'user_id' => $user->id,
        ]);

        $this->postJson(route('registration.code'), [
            'code' => random_int(1000,9999),
            'user_id' => $user->id,
        ], $this->apiHeaders($user->api_token))->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'text'
                ]
            ]);
    }

    public function testRegistrationValidator()
    {
        $this->postJson(route('registration'), [
            'phone' => 'badPhoneNumber',
            'os' => 'android',
            'token' => "AndroidDeviceToken",
        ])->assertStatus(422);

        $this->postJson(route('registration'), [
            'phone' => 'badPhoneNumber',
            'os' => 'badOs',
            'token' => "badOsDeviceToken",
        ])->assertStatus(422);

        $this->postJson(route('registration'), [
            'phone' => 'badPhoneNumber',
            'os' => 'ios',
            'token' => null,
        ])->assertStatus(422);
    }
}
