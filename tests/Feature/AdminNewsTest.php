<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminNewsTest extends TestCase
{
    use DatabaseTransactions;

    public function testBadAddNews()
    {
        $bad_token = random_int(100,200);
        $product = factory(Product::class)->create();

        $this->postJson(route('news.store'), factory(News::class)->make([
            'product_id' => $product->id,
        ])->toArray(), $this->apiHeaders($bad_token, 'Token'))->assertStatus(403);
    }

    public function testAddNews()
    {
        config(['app.admin_hash' => '123456']);

        $token = config('app.admin_hash');

        $product = factory(Product::class)->create();

        $this->postJson(route('news.store'), $record = factory(News::class)->make([
            'product_id' => $product->id,
            'type' => 'main',
        ])->toArray(), $this->apiHeaders($token, 'Token'))->assertStatus(200);

        $this->assertNotNull(News::whereTitle($record['title'])->first());
    }
}
