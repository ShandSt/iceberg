<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Service\OneC\Actions\AddressAction;
use App\User;
use Psr\Http\Message\ResponseInterface;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AddressActionTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStore()
    {
        $service = new AddressAction;
        $response = $service->store(factory(Address::class)->make()->toArray());

        $this->assertInstanceOf(ResponseInterface::class,$response);
        $this->assertTrue($response->getStatusCode() === 200);
    }

    public function testFindAddressByUser()
    {
        $service = new AddressAction;
        $response = $service->findByUser(factory(User::class)->create()->guid);

        $this->assertInstanceOf(ResponseInterface::class,$response);
        $this->assertTrue($response->getStatusCode() === 200);
    }

    public function testDelete()
    {
        $service = new AddressAction;
        $response = $service->delete(factory(Address::class)->make()->toArray()['guid']);

        $this->assertInstanceOf(ResponseInterface::class,$response);
        $this->assertTrue($response->getStatusCode() === 200);
    }

    public function testShow()
    {
        $service = new AddressAction;
        $response = $service->show(factory(Address::class)->make()->toArray()['guid']);

        $this->assertInstanceOf(ResponseInterface::class,$response);
        $this->assertTrue($response->getStatusCode() === 200);
    }

    public function testUpdate()
    {
        $service = new AddressAction;
        $address = factory(Address::class)->make()->toArray();
        $response = $service->update($address['guid'], $address);

        $this->assertInstanceOf(ResponseInterface::class,$response);
        $this->assertTrue($response->getStatusCode() === 200);
    }
}
