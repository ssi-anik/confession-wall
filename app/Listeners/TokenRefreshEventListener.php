<?php

namespace App\Listeners;

use App\Events\TokenRefreshEvent;

class TokenRefreshEventListener
{
    public function handle (TokenRefreshEvent $event) {
        custom_logger([ 'user.token-refresh.success' => [ 'id' => $event->user->id ] ]);
    }
}
