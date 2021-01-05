<?php

namespace App\Http\Controllers\Api;

use App\Events\UserSettingsUpdateEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSettingsRequest;
use App\Models\User;

class SettingsController extends Controller
{
    public function updateSettings (UpdateSettingsRequest $request) {
        $user = auth()->user();
        $changes = [];

        if ($newPassword = $request->input('new_password')) {
            $currentPassword = $request->input('current_password');
            if (false === auth()->validate([ 'email' => $user->email, 'password' => $currentPassword ])) {
                return response()->json([ 'error' => true, 'message' => 'Incorrect password.', ], 403);
            }

            $changes['password'] = $newPassword;
        }

        if ($name = trim($request->input('name'))) {
            $changes['name'] = trim($request->input('name'));
        }

        if ($email = trim($request->get('email'))) {
            $emailUser = User::where('email', $email)->first();
            if ($emailUser && $emailUser->id != $user->id) {
                return response()->json([ 'error' => true, 'message' => 'Email is already taken.' ], 403);
            }

            if (!$emailUser) {
                $changes['email'] = $email;
            }
        }

        if (empty($changes)) {
            return response()->json([ 'error' => true, 'message' => 'Nothing to change.' ], 403);
        }

        foreach ( $changes as $field => $value ) {
            $user->{$field} = $value;
        }

        $user->save();
        event(new UserSettingsUpdateEvent($user, $changes));

        return response()->json([ 'error' => false, 'message' => 'Profile has been updated successfully.' ], 202);
    }
}
