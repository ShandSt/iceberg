<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Observers\AddressObserver;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AddressTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUpdateGuidAfterCreateNewItem()
    {
        $address = factory(Address::class)->create();
        $observer = new AddressObserver;
        $observer->created($address);

        $changed = Address::find($address->id);

        $this->assertNotNull($changed->guid);
    }

    public function testChangeAddress()
    {
        $user = factory(User::class)->create();
        $address = factory(Address::class)->create();

        $address->users()->attach($user->id);

        $this->putJson(route('user.address.change', ['id' => $address->id]), [
            'street' => 'New street',
            'house' => 'New house',
            'entrance' => 555,
            'floor' => 666,
            'apartment' => 777,
            'comment' => 'New comment',
            'city_id' => $address->city_id,
        ], $this->apiHeaders($user->api_token))->assertStatus(200);

        $find = Address::find($address->id);

        $this->assertEquals('New street', $find->street);
        $this->assertEquals('New house', $find->house);
        $this->assertEquals(555, $find->entrance);
        $this->assertEquals(666, $find->floor);
        $this->assertEquals(777, $find->apartment);
    }
}
