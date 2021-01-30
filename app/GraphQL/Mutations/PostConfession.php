<?php

namespace App\GraphQL\Mutations;

use App\GraphQL\Types\Error;
use App\GraphQL\Types\Success;
use App\Models\Confession;
use App\Models\User;

class PostConfession
{
    public function __invoke ($_, array $args) {
        $body = $args['body'];
        $requestingUser = auth()->user();
        $username = $args['username'];

        /** @var User|null $lookupUser */
        $lookupUser = User::where('username', $username)->first();
        if (!$lookupUser || $lookupUser->isBanned()) {
            return new Error([ 'message' => 'Invalid user.' ]);
        }

        if ($requestingUser && $requestingUser->id == $lookupUser->id) {
            return new Error([ 'message' => 'You cannot post on your wall.' ]);
        }

        if (is_null($requestingUser)) {
            $privacy = User::MSG_FROM_PUBLIC;
        } elseif ($args['as_anonymous'] ?? false) {
            $privacy = User::MSG_FROM_ANONYMOUS;
        } else {
            $privacy = User::MSG_FROM_REGISTERED_USER;
        }

        if (false === $lookupUser->canReceiveWith($privacy)) {
            return new Error([ 'message' => $lookupUser->whyCannotReceiveTranslation(), ]);
        }

        Confession::create([
            'receiver_id'  => $lookupUser->id,
            'body'         => $body,
            'poster_id'    => $requestingUser->id ?? null,
            'is_anonymous' => (bool) ($args['as_anonymous'] ?? false) ? true : false,
        ]);

        return new Success([ 'message' => 'Successfully posted on ' . $username . '\'s wall.', ]);
    }
}
