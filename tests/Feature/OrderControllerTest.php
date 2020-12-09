<?php

namespace Tests\Feature;

use App\Events\Api\OnOrderCreated;
use App\Exceptions\Handler;
use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCanNotAddOrderWithoutProducts()
    {
        $user = factory(User::class)->create();
        $address = factory(Address::class)->create();

        Event::fake();

        $this->postJson(route('order.store'), factory(Order::class)->make([
            'address_id' => $address->id,
        ])->toArray(),
            $this->apiHeaders($user->api_token)
        )->assertStatus(400);

        Event::assertNotDispatched(OnOrderCreated::class, function (OnOrderCreated $e) {
            return $e->getOrder() instanceof Order;
        });
    }

    public function testCreateOrderWithProducts()
    {
        $user = factory(User::class)->create();
        $address = factory(Address::class)->create();

        $product = factory(Product::class)->create();

        Event::fake();

        $this->postJson(route('order.store'), factory(Order::class)->make([
            'address_id' => $address->id,
            'products' => [
                [
                    'id' => $product->id,
                    'count' => 1,
                ],
            ],
        ])->toArray(),
            $this->apiHeaders($user->api_token)
        )->assertStatus(200);

        Event::assertDispatched(OnOrderCreated::class, function (OnOrderCreated $e) {
            return $e->getOrder() instanceof Order;
        });
    }

    public function testCheckOrderStatus()
    {
        $user = factory(User::class)->create();
        $address = factory(Address::class)->create();
        $order = factory(Order::class)->create([
            'user_id' => $user->id,
            'address_id' => $address->id,
        ]);

        $this->getJson(route('order.show', ['id' => $order->id]), $this->apiHeaders($user->api_token))
            ->assertStatus(200);
    }

    public function testCheckNotMyOrderStatus()
    {
        $concrete = factory(User::class);
        $a = $concrete->create();
        $b = $concrete->create();
        $address = factory(Address::class)->create();
        $order = factory(Order::class)->create([
            'user_id' => $a->id,
            'address_id' => $address->id,
        ]);

        $this->getJson(route(
            'order.show',[
                'id' => $order->id,
            ]
        ), $this->apiHeaders($b->api_token))->assertStatus(404);
    }
}
