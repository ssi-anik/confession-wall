<?php

namespace App\Listeners;

use App\Events\UserLoginEvent;

class UserLoginEventListener
{
    public function handle (UserLoginEvent $event) {
        custom_logger([ 'user.login.success' => [ 'id' => $event->user->id ] ]);
    }
}
