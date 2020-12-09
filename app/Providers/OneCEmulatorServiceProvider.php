<?php

namespace App\Providers;

use App\Service\OneC\Client\Base;
use App\Service\OneC\Contracts\OneCClientContract;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class OneCEmulatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->environment('testing', 'local')) {
            config([
                'onec.endpoint' => sprintf('%s/1c-dummy/', env("APP_URL", "http://iceberg.dev")),
                'onec.ping_endpoint' => sprintf("%s/1c-dummy/ping", env("APP_URL", "http://iceberg.dev")),
            ]);

            $this->bootOneCDummyRoutes();
        }


        $this->app->bind(OneCClientContract::class, Base::class);

    }


    private function bootOneCDummyRoutes()
    {
        Route::group(['prefix' => '1c-dummy'], function () {
            require base_path('routes/1c-dummy.php');
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
