<?php

namespace App\Listeners;

use App\Events\UserSettingsUpdateEvent;

class UserSettingsUpdateEventListener
{
    public function handle (UserSettingsUpdateEvent $event) {
        custom_logger([ 'user.settings.update' => [ 'id' => $event->user->id ] ]);
    }
}
