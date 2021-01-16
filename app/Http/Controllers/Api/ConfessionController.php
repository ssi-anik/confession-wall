<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ConfessionController extends Controller
{
    public function getUserInfo (Request $request, $username) {
        $requestingUser = auth()->user();

        /** @var User|null $lookupUser */
        $lookupUser = User::where('username', $username)->first();
        if (!$lookupUser || $lookupUser->isBanned()) {
            return response()->json([ 'error' => true, 'message' => 'Invalid user.' ], 401);
        }

        if ($requestingUser && $requestingUser->id == $lookupUser->id) {
            return response()->json([ 'error' => true, 'message' => 'You cannot post on your wall.' ], 403);
        }

        $canPostOnWall = true;
        $message = sprintf('Write a confession on %s\'s wall!', $lookupUser->name);

        if (is_null($requestingUser)) {
            $privacy = User::MSG_FROM_PUBLIC;
        } elseif ($request->has('as_anonymous')) {
            $privacy = User::MSG_FROM_ANONYMOUS;
        } else {
            $privacy = User::MSG_FROM_REGISTERED_USER;
        }

        if (false === $lookupUser->canReceiveWith($privacy)) {
            $canPostOnWall = false;
            $message = $lookupUser->whyCannotReceiveTranslation();
        }

        return response()->json([
            'error'   => false,
            'message' => $message,
            'data'    => [
                'can_post_on_wall' => $canPostOnWall,
            ],
        ], 200); // why 200? cause requesting user has nothing to do with looking for user's permission
    }
}
