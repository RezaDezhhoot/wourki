<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Cart'                     => 'App\Policies\CartPolicy',
        'App\Address'                  => 'App\Policies\AddressPolicy',
        'App\Store'                    => 'App\Policies\StorePolicy',
        'App\Store_photo'              => 'App\Policies\StorePhotoPolicy',
        'App\ProductSeller'            => 'App\Policies\ProductPolicy',
        'App\Product_seller_photo'     => 'App\Policies\ProductPhotoPolicy',
        'App\Product_seller_attribute' => 'App\Policies\AttributePolicy',
        'App\Bill'                     => 'App\Policies\BillPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Passport::routes();

        Passport::tokensExpireIn(Carbon::now()->addDays(15));

        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
    }
}
