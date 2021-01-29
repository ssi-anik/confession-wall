<?php

namespace App\GraphQL\Mutations;

use App\Events\UserAccountCreateEvent;
use App\GraphQL\Types\Error;
use App\GraphQL\Types\Success;
use App\Models\User;
use Throwable;

class CreateAccount
{
    public function __invoke ($_, array $args) {
        try {
            app('db')->beginTransaction();
            $user = User::create([
                'name'     => trim($args['name']),
                'username' => $args['username'],
                'email'    => $args['email'],
                'password' => $args['password'],
            ]);
        } catch ( Throwable $t ) {
            app('db')->rollback();
            custom_logger([ 'user.create-account.error' => [ 'exception' => $t->getMessage() ] ]);

            return new Error([ 'message' => 'Something went wrong. Please try again later.', ]);
        }

        event(new UserAccountCreateEvent($user));
        app('db')->commit();

        return new Success([ 'message' => 'Your account is created successfully. Thanks for signing up.', ]);
    }
}
