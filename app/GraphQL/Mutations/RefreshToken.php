<?php

namespace App\GraphQL\Mutations;

use App\Events\TokenRefreshEvent;
use App\GraphQL\Types\LoginPayload;

class RefreshToken
{
    public function __invoke ($_, array $args) {
        $user = auth()->user();

        $token = auth()->fromUser($user);
        event(new TokenRefreshEvent($user));

        // run `auth()->invalidate(true);` if you want to invalidate the token
        return new LoginPayload([
            'access_token' => $token,
            'type'         => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
        ]);
    }
}
