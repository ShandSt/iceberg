<?php

namespace Tests\Feature;

use App\Models\Product;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetProducts()
    {
        $user = factory(User::class)->create();

        factory(Product::class)->times(10);

        $this->getJson(route('products'),
            $this->apiHeaders($user->api_token))
            ->assertStatus(200);
    }
}
