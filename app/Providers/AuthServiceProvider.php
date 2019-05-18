<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\UserAddress;
use App\Policies\OrderPolicy;
use App\Models\Order;
use App\Policies\UserAddressPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 收货地址授权策略注册
        UserAddress::class => UserAddressPolicy::class,
        // 订单授权策略注册
        Order::class       => OrderPolicy::class,
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
