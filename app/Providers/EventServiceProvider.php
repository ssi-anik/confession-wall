<?php

namespace App\Providers;

use App\Events\UserAccountCreateEvent;
use App\Listeners\UserAccountCreateEventListener;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserAccountCreateEvent::class => [
            UserAccountCreateEventListener::class,
        ],
    ];
}
