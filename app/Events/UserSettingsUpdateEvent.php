<?php

namespace App\Events;

use App\Models\User;

class UserSettingsUpdateEvent extends Event
{
    public $user, $changes;

    public function __construct (User $user, array $changes) {
        $this->user = $user;
        $this->changes = $changes;
    }
}
