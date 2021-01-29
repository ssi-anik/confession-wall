<?php

namespace App\GraphQL\Mutations;

use App\Events\UserLoginEvent;
use App\GraphQL\Types\Error;
use App\GraphQL\Types\LoginPayload;

class UserLogin
{
    public function __invoke ($_, array $args) {
        $username = $args['username'];
        $password = $args['password'];
        $field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $token = auth()->attempt([ $field => $username, 'password' => $password ]);

        if (false === $token) {
            return new Error([ 'message' => 'Username or password is invalid.' ]);
        }

        if (($user = auth()->user())->is_banned) {
            custom_logger([ 'user.login.banned-access' => [ 'id' => $user->id ] ]);

            return new Error([ 'message' => 'Your account is unavailable at this moment.' ]);
        }

        event(new UserLoginEvent($user));

        return new LoginPayload([
            'access_token' => $token,
            'type'         => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
        ]);
    }
}
