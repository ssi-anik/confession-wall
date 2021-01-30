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
                'items'      => $results->getCollection()->transform(function ($row) {
                    return [
                        'id'           => $row->id,
                        'body'         => $row->body,
                        'is_public'    => $row->poster ? false : true,
                        'is_anonymous' => $row->poster && $row->is_anonymous,
                        'poster'       => !$row->is_anonymous && $row->poster ? $row->poster->username : null,
                        'posted_at'    => $row->created_at->toDateTimeString(),
                    ];
                }),
                'pagination' => [
                    'has_prev' => $results->previousPageUrl() ? true : false,
                    'has_next' => $results->hasMorePages(),
                ],
            ],
        ]);
    }

    public function deleteConfession ($confessionId) {
        $confession = Confession::where('receiver_id', auth()->user()->id)->find($confessionId);

        if (!$confession) {
            return response()->json([
                'error'   => true,
                'message' => 'The confession either does not exist or belongs to you.',
            ], 403);
        }

        $confession->delete();

        return response()->json([ 'error' => false, 'message' => 'The confession is successfully deleted.' ], 202);
    }
}
