<?php

namespace App\Listeners;

use App\Events\UserAccountCreateEvent;

class UserAccountCreateEventListener
{
    public function handle (UserAccountCreateEvent $event) {
        custom_logger([ 'user.create-account.create' => [ 'id' => $event->user->id ] ]);
        // TODO: send sign up mail, verify if required
    }
}
