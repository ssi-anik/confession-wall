<?php

namespace App\Http\Controllers\Api;

use App\Events\UserAccountCreateEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAccountRequest;
use App\Models\User;
use Throwable;

class AuthController extends Controller
{
    public function createAccount (CreateAccountRequest $request) {
        try {
            app('db')->beginTransaction();
            $user = User::create([
                'name'     => trim($request->input('name')),
                'username' => $request->input('username'),
                'email'    => $request->input('email'),
                'password' => $request->input('password'),
            ]);
        } catch ( Throwable $t ) {
            app('db')->rollback();
            custom_logger([ 'user.create-account.error' => [ 'exception' => $t->getMessage() ] ]);

            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }

        event(new UserAccountCreateEvent($user));
        app('db')->commit();

        return response()->json([
            'error'   => false,
            'message' => 'Your account is created successfully. Thanks for signing up.',
        ], 201);
    }
}
