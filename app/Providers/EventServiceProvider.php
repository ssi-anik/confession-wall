<?php

namespace App\Providers;

use App\Events\TokenRefreshEvent;
use App\Events\UserAccountCreateEvent;
use App\Events\UserLoginEvent;
use App\Events\UserSettingsUpdateEvent;
use App\Listeners\TokenRefreshEventListener;
use App\Listeners\UserAccountCreateEventListener;
use App\Listeners\UserLoginEventListener;
use App\Listeners\UserSettingsUpdateEventListener;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserAccountCreateEvent::class => [
            UserAccountCreateEventListener::class,
        ],

        UserLoginEvent::class => [
            UserLoginEventListener::class,
        ],

        TokenRefreshEvent::class => [
            TokenRefreshEventListener::class,
        ],

        UserSettingsUpdateEvent::class => [
            UserSettingsUpdateEventListener::class,
        ],
    ];
}
