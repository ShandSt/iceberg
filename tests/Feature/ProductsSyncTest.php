<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Mockery as m;
use App\Console\Commands\OneCSyncProducts;
use App\Service\OneC\Actions\ProductsAction;
use App\Service\OneC\Server\OneCServer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductsSyncTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSyncCommand()
    {

        $this->artisan('1c:sync:products');

        $this->assertTrue(Product::count() > 0);
    }
}
