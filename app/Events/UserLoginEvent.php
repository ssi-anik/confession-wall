<?php

namespace App\Events;

use App\Models\User;

class UserLoginEvent extends Event
{
    public $user;

    public function __construct (User $user) {
        $this->user = $user;
    }
}
