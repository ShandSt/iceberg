<?php

namespace Tests\Feature;

use App\Events\Api\OnOrderPaymentStatusChanged;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderSyncCommandTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSyncOrder()
    {
        Event::fake();

        $this->artisan('1c:sync:orders');

        Event::assertDispatched(OnOrderPaymentStatusChanged::class);

    }
}
