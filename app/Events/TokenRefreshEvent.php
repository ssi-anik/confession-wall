<?php

namespace App\Events;

use App\Models\User;

class TokenRefreshEvent extends Event
{
    public $user;

    public function __construct (User $user) {
        $this->user = $user;
    }
}
