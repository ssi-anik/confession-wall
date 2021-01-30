<?php

namespace App\Http\Controllers\Api;

use App\Events\UserSettingsUpdateEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSettingsRequest;
use App\Models\User;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            $changes['name'] = $name;
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

        if ($request->has('message_privacy')) {
            $changes['message_privacy'] = (int) $request->input('message_privacy');
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

    public function updateProfilePicture (Request $request) {
        $this->validate($request, [
            'profile_picture'        => [ 'required_without:remove_profile_picture', 'image' ],
            'remove_profile_picture' => [ 'required_without:profile_picture' ],
        ]);

        $profilePicture = null;
        if ($request->hasFile('profile_picture')) {
            $profilePicture = $request->file('profile_picture')->store('avatars');
        }

        $user = auth()->user();
        $user->profile_picture = $profilePicture;
        $user->save();

        return response()->json([
            'error'   => false,
            'message' => sprintf('Successfully %s your profile picture.', $profilePicture ? 'saved' : 'removed'),
        ]);
    }

    public function getUserSettings () {
        $user = auth()->user();

        return response()->json([
            'error' => false,
            'data'  => [
                'name'            => $user->name,
                'email'           => $user->email,
                'username'        => $user->username,
                'profile_picture' => $user->profilePicture(),
                'message_privacy' => $user->message_privacy,
            ],
        ]);
    }
}
