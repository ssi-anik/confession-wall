<?php

namespace App\GraphQL\Mutations;

use App\Events\UserSettingsUpdateEvent;
use App\GraphQL\Types\Error;
use App\GraphQL\Types\Success;
use App\Models\User;

class UpdateSetting
{
    public function __invoke ($_, array $args) {
        $user = auth()->user();
        $changes = [];

        if (isset($args['new_password']) && ($newPassword = $args['new_password'])) {
            $currentPassword = $args['current_password'];
            if (false === auth()->validate([ 'email' => $user->email, 'password' => $currentPassword ])) {
                return new Error([ 'message' => 'Incorrect password.', ]);
            }

            $changes['password'] = $newPassword;
        }

        if (isset($args['name']) && ($name = trim($args['name']))) {
            $changes['name'] = trim($args['name']);
        }

        if (isset($args['email']) && $email = trim($args['email'])) {
            $emailUser = User::where('email', $email)->first();
            if ($emailUser && $emailUser->id != $user->id) {
                return new Error([ 'message' => 'Email is already taken.', ]);
            }

            if (!$emailUser) {
                $changes['email'] = $email;
            }
        }

        if (isset($args['message_privacy'])) {
            $changes['message_privacy'] = (int) $args['message_privacy'];
        }

        if (empty($changes)) {
            return new Error([ 'message' => 'Nothing to change.', ]);
        }

        foreach ( $changes as $field => $value ) {
            $user->{$field} = $value;
        }

        $user->save();
        event(new UserSettingsUpdateEvent($user, $changes));

        return new Success([ 'message' => 'Profile has been updated successfully.']);
    }
}
