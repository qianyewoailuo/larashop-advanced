<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Registered;
use App\Listeners\RegisteredListener;
use App\Events\OrderReviewd;
use App\Listeners\UpdateProductRating;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     * 注册事件与监听处理的关联
     *
     * @var array
     */
    protected $listen = [
        // 注册监听
        Registered::class => [
            RegisteredListener::class
        ],

        // 评分监听
        OrderReviewd::class => [
            UpdateProductRating::class
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
