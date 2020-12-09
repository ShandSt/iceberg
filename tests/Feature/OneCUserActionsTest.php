<?php

namespace Tests\Feature;

use App\Service\OneC\Actions\UserAction;
use App\Service\OneC\Contracts\OneCClientContract;
use App\User;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery as m;

class OneCUserActionsTest extends TestCase
{

    public function testOneCGetAddUser()
    {
        /**
         * @var $driver UserAction
         */
        $driver = $this->app->make(UserAction::class);

        $user = factory(User::class)->create([
            'guid' => str_random(20)
        ]);

        $this->assertTrue($driver->addUser($user->toArray()));
    }

    public function testOneCFindUserByPhone()
    {
        /**
         * @var $driver UserAction
         */
        $driver = $this->app->make(UserAction::class);

        $user = factory(User::class)->create([
            'guid' => str_random(20)
        ]);

        $this->assertInstanceOf(Response::class, $result = $driver->findByPhone($user->phone));
        $this->assertTrue($result->getStatusCode() === 200);
    }

    public function testOneCGetUserBottles()
    {
        /**
         * @var $driver UserAction
         */
        $driver = $this->app->make(UserAction::class);

        $user = factory(User::class)->create([
            'guid' => str_random(20)
        ]);

        $this->assertInstanceOf(Response::class, $result = $driver->getBottles($user->guid));
        $this->assertTrue($result->getStatusCode() === 200);
    }

    public function testOneCUpdateBottles()
    {
        /**
         * @var $driver UserAction
         */
        $driver = $this->app->make(UserAction::class);

        $user = factory(User::class)->create([
            'guid' => str_random(20)
        ]);


        $this->assertInstanceOf(Response::class, $result = $driver->setBottles($user->guid,[
            'guess' => 200
        ]));
        $this->assertTrue($result->getStatusCode() === 200);
    }
}
