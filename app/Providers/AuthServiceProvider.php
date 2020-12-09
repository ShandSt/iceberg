<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\City;
use App\Models\ConfirmCode;
use App\Models\ProductNew;
use App\Models\Tag;
use App\Policies\CategoryPolicy;
use App\Policies\CityPolicy;
use App\Policies\ConfirmCodePolicy;
use App\Policies\ProductPolicy;
use App\Policies\TagPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Category::class => CategoryPolicy::class,
        Tag::class => TagPolicy::class,
        ProductNew::class => ProductPolicy::class,
        City::class => CityPolicy::class,
        ConfirmCode::class => ConfirmCodePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
