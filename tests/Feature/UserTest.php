<?php

namespace Tests\Feature;

use App\Events\Api\OnConsumptionUpdate;
use App\Events\Api\OnUserAdressAdded;
use App\Events\Api\OnUserProfileUpdated;
use App\Models\Address;
use App\Models\UserAdress;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetData()
    {
        $user = factory(User::class)->create();

        $this->getJson(route('user.index'), $this->apiHeaders($user->api_token))
            ->assertStatus(200)
            ->assertJson($user->load('settings','consumption','adress')->toArray());
    }

    public function testUpdateUser()
    {
        $user = factory(User::class)->create();

        Event::fake();

        $this->postJson(route('user.update'), [
            'first_name' => 'Ivan',
            'last_name' => 'Ivanov',
            'company_name' => 'Ivanov LLC',
            'inn' => 'Ivanov228'
        ], $this->apiHeaders($user->api_token))
            ->assertStatus(200);

        Event::assertDispatched(OnUserProfileUpdated::class);

        $find_user = User::find($user->id);

        $this->assertEquals('Ivan', $find_user->first_name);
        $this->assertEquals('Ivanov', $find_user->last_name);
        $this->assertEquals('Ivanov LLC', $find_user->company_name);
        $this->assertEquals('Ivanov228', $find_user->inn);
    }

    public function testUpdateUserWithoutCompanyAndInn()
    {
        $user = factory(User::class)->create();

        Event::fake();

        $this->postJson(route('user.update'), [
            'first_name' => 'Ivan',
            'last_name' => 'Ivanov',
        ], $this->apiHeaders($user->api_token))
            ->assertStatus(200);

        Event::assertDispatched(OnUserProfileUpdated::class);

        $find_user = User::find($user->id);

        $this->assertEquals('Ivan', $find_user->first_name);
        $this->assertEquals('Ivanov', $find_user->last_name);
        $this->assertEquals($user->company_name, $find_user->company_name);
        $this->assertEquals($user->inn, $find_user->inn);
    }

    public function testUpdateUserWithoutCompany()
    {
        $user = factory(User::class)->create();

        Event::fake();

        $this->postJson(route('user.update'), [
            'first_name' => 'Ivan',
            'last_name' => 'Ivanov',
            'inn' => 'Inn'
        ], $this->apiHeaders($user->api_token))
            ->assertStatus(200);

        Event::assertDispatched(OnUserProfileUpdated::class);

        $find_user = User::find($user->id);

        $this->assertEquals('Ivan', $find_user->first_name);
        $this->assertEquals('Ivanov', $find_user->last_name);
        $this->assertEquals($user->company_name, $find_user->company_name);
        $this->assertEquals('Inn', $find_user->inn);
    }


    public function testUpdateUserWithoutInn()
    {
        $user = factory(User::class)->create();

        Event::fake();

        $this->postJson(route('user.update'), [
            'first_name' => 'Ivan',
            'last_name' => 'Ivanov',
            'company_name' => 'Ivanov228'
        ], $this->apiHeaders($user->api_token))
            ->assertStatus(200);

        Event::assertDispatched(OnUserProfileUpdated::class);

        $find_user = User::find($user->id);

        $this->assertEquals('Ivan', $find_user->first_name);
        $this->assertEquals('Ivanov', $find_user->last_name);
        $this->assertEquals('Ivanov228', $find_user->company_name);
        $this->assertEquals($user->inn, $find_user->inn);
    }

    public function testAddAdress()
    {

        Event::fake();

        $user = factory(User::class)->create();

        $address = factory(Address::class)->raw();
        $this->postJson(route('user.address.add'), $address, $this->apiHeaders($user->api_token))->assertStatus(200);

        Event::assertDispatched(OnUserAdressAdded::class, function (OnUserAdressAdded $e) use ($user) {
            return $e->getAdress() instanceof Address;
        });

        $this->getJson(route('user.address'), $this->apiHeaders($user->api_token))
            ->assertStatus(200);

    }
}
