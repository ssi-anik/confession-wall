<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Confession;
use App\Models\User;
use Illuminate\Http\Request;

class ConfessionController extends Controller
{
    public function postConfession (Request $request) {
        $body = $request->input('body');
        if (empty($body)) {
            return response()->json([ 'error' => true, 'message' => 'Cannot post empty confession' ], 422);
        }

        $requestingUser = auth()->user();
        $username = $request->input('username');

        /** @var User|null $lookupUser */
        $lookupUser = User::where('username', $username)->first();
        if (!$lookupUser || $lookupUser->isBanned()) {
            return response()->json([ 'error' => true, 'message' => 'Invalid user.' ], 401);
        }

        if ($requestingUser && $requestingUser->id == $lookupUser->id) {
            return response()->json([ 'error' => true, 'message' => 'You cannot post on your wall.' ], 403);
        }

        if (is_null($requestingUser)) {
            $privacy = User::MSG_FROM_PUBLIC;
        } elseif ($request->has('as_anonymous')) {
            $privacy = User::MSG_FROM_ANONYMOUS;
        } else {
            $privacy = User::MSG_FROM_REGISTERED_USER;
        }

        if (false === $lookupUser->canReceiveWith($privacy)) {
            return response()->json([
                'error'   => true,
                'message' => $lookupUser->whyCannotReceiveTranslation(),
            ], 403);
        }

        Confession::create([
            'receiver_id'  => $lookupUser->id,
            'body'         => $body,
            'poster_id'    => $requestingUser->id ?? null,
            'is_anonymous' => $request->input('as_anonymous') == 1 ? true : false,
        ]);

        return response()->json([
            'error'   => false,
            'message' => 'Successfully posted on ' . $username . '\'s wall.',
        ], 200);
    }

    public function myConfessions (Request $request) {
        $user = auth()->user();

        $results = Confession::with('poster')->where('receiver_id', $user->id)->latest()->paginate(10);

        return response()->json([
            'error' => false,
            'data'  => [
                'data' => [
                    'items'      => $results->getCollection()->transform(function ($each) {
                        return [
                            'body'         => $each->body,
                            'is_public'    => $each->poster ? false : true,
                            'is_anonymous' => $each->poster && $each->is_anonymous,
                            'poster'       => !$each->is_anonymous && $each->poster ? $each->poster->username : null,
                            'posted_at'    => $each->created_at->toDateTimeString(),
                        ];
                    }),
                    'pagination' => [
                        'has_next' => $results->hasMorePages(),
                    ],
                ],
            ],
        ]);
    }
}
