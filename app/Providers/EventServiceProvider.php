<?php

namespace App\Providers;

use App\Events\Api\OnAppLaunch;
use App\Events\Api\OnConsumptionUpdate;
use App\Events\Api\OnOrderCreated;
use App\Events\Api\OnOrderCreatedOneC;
use App\Events\Api\OnOrderPaymentStatusChanged;
use App\Events\Api\OnOrderUpdated;
use App\Events\Api\OnUserAdressAdded;
use App\Events\Api\OnUserBalanceChanged;
use App\Events\Api\OnUserLogin;
use App\Events\Api\OnUserPhoneUpdated;
use App\Events\Api\OnUserProfileUpdated;
use App\Events\Api\UserCreated;
use App\Events\Api\OnAlmostOutOfWater;
use App\Events\Api\OnNewsCreated;
use App\Events\Api\UserCreatedFromSite;
use App\Listeners\Api\GetUserOrdersFromOneC;
use App\Listeners\Api\SendConsumptionToOneC;
use App\Listeners\Api\SendOrderToOneCAfterCreate;
use App\Listeners\Api\SendOrderToOneCAfterUpdate;
use App\Listeners\Api\SendPushOnOrderDeliveryDatesUpdated;
use App\Listeners\Api\SendPushOnOrderStatusChanged;
use App\Listeners\Api\SendPushOnAlmostOutOfWater;
use App\Listeners\Api\SendPushOnNewsCreated;
use App\Listeners\Api\SendPushToUserOnBalanceUpdated;
use App\Listeners\Api\SendSmsConfirmatonOnUserLogin;
use App\Listeners\Api\SendSmsToUserAfterPhoneUpdating;
use App\Listeners\Api\SendSmsToUserAfterRegistration;
use App\Listeners\Api\SendUserToOneCAfterLogin;
use App\Listeners\Api\UpdateUserInOneCServerAfterProfileUpdated;
use App\Listeners\SendUserAdressToOneC;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UserCreated::class => [
            SendSmsToUserAfterRegistration::class,
            SendUserToOneCAfterLogin::class,
        ],
        UserCreatedFromSite::class => [
            //SendSmsToUserAfterRegistration::class,
            SendUserToOneCAfterLogin::class,
        ],
        OnUserLogin::class => [
            SendSmsConfirmatonOnUserLogin::class,
        ],
        OnUserProfileUpdated::class => [
            UpdateUserInOneCServerAfterProfileUpdated::class,
        ],
        OnConsumptionUpdate::class => [
            SendConsumptionToOneC::class,
        ],
        OnUserBalanceChanged::class => [
            //SendPushToUserOnBalanceUpdated::class,
        ],
        OnUserAdressAdded::class => [
            SendUserAdressToOneC::class,
        ],
        OnOrderCreated::class => [
            SendOrderToOneCAfterCreate::class,
        ],
        OnOrderCreatedOneC::class => [
            //SendPushOnOrderDeliveryDatesUpdated::class,
        ],
        OnOrderUpdated::class => [
            SendOrderToOneCAfterUpdate::class,
        ],
        OnUserPhoneUpdated::class => [
            SendSmsToUserAfterPhoneUpdating::class,
        ],
        OnAppLaunch::class => [
            GetUserOrdersFromOneC::class,
        ],
        OnAlmostOutOfWater::class => [
            SendPushOnAlmostOutOfWater::class,
        ],
        OnNewsCreated::class => [
            SendPushOnNewsCreated::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
