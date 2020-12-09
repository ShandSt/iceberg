<?php

namespace Tests\Feature;

use App\Service\Sms\Contracts\SmsServiceContract;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SmsServiceTest extends TestCase
{
    public function testSendSms()
    {
        $service = $this->app->make(SmsServiceContract::class);

        $this->assertTrue(
            $service->sendSms('+380675906752', 'Sms message')
        );
    }
}
