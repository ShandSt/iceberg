<?php

namespace App\Providers;

use App\Models\Address;
use App\Observers\AddressObserver;
use App\Observers\UserObserver;
use App\Service\Billing\Contracts\BillingServiceContract;
use App\Service\Push\Contracts\PushMessageContract;
use App\Service\Push\Contracts\PushServiceContract;
use App\Service\Push\Exception\DriverNotFoundException;
use App\Service\Push\Exception\MessageEntityNotFoundException;
use App\Service\Sms\Contracts\SmsServiceContract;
use App\Service\Sms\Driver\SmsruDriver;
use App\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Exception;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Schema::defaultStringLength(191);
        $this->initSmsServiceDriver();

        User::observe(UserObserver::class);
        Address::observe(AddressObserver::class);

        $this->initPushDriver();

        $this->initBillingServiceDriver();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    private function initSmsServiceDriver()
    {
        $this->app->bind(SmsServiceContract::class,function (Application $app) {
            $config = config('services.sms');

            if (! isset($config['drivers'][$config['driver']])) {
                throw new \InvalidArgumentException(sprintf(
                    "Sms driver %s not found!",
                    $config['driver']
                ));
            }

            return $app->make($config['drivers'][$config['driver']]);
        });
    }


    private function initPushDriver()
    {
        $this->app->bind(PushServiceContract::class, function ($app) {
            $config = config('services.push');

            if (! isset($config['drivers'][$config['driver']]['service'])) {
                throw new DriverNotFoundException(
                    sprintf(
                        "Push service %s not found.", $config['driver']
                    )
                );
            }

            return $app->make($config['drivers'][$config['driver']]['service']);
        });

        $this->app->bind(PushMessageContract::class, function ($app) {
            $config = config('services.push');

            if (! isset($config['drivers'][$config['driver']]['message'])) {
                throw new MessageEntityNotFoundException(sprintf("
                    Message driver %s not found
                ", $config['drivers'][$config['driver']]['message']));
            }

            return $app->make($config['drivers'][$config['driver']]['message']);
        });
    }

    private function initBillingServiceDriver()
    {
        $this->app->bind(BillingServiceContract::class, function (Application $app) {
            $config = config('services.billing');

            if (! isset($config['drivers'][$config['driver']])) {
                throw new \InvalidArgumentException(sprintf(
                    "Billing driver %s not found!",
                    $config['driver']
                ));
            }
            return $app->make($config['drivers'][$config['driver']]);
        });
    }
}
