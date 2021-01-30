<?php

namespace App\GraphQL\Queries;

class UserSettings
{
    public function __invoke ($_, array $args) {
        $user = auth()->user();

        return [
            'name'            => $user->name,
            'email'           => $user->email,
            'username'        => $user->username,
            'profile_picture' => $user->profilePicture(),
            'message_privacy' => $user->message_privacy,
        ];
    }
}
