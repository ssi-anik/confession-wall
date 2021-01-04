<?php

namespace App\Http\Controllers\Api;

use App\Events\TokenRefreshEvent;
use App\Events\UserAccountCreateEvent;
use App\Events\UserLoginEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAccountRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Throwable;

class AuthController extends Controller
{
    public function prepareTokenData ($token) {
        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
        ];
    }

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

    public function loginToAccount (Request $request) {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);
        $username = $request->input('username');
        $password = $request->input('password');
        $field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $token = auth()->attempt([ $field => $username, 'password' => $password ]);
        if (false === $token) {
            return response()->json([
                'error'   => true,
                'message' => 'Username or password is invalid.',
            ], 401);
        }

        if (($user = auth()->user())->is_banned) {
            custom_logger([ 'user.login.banned-access' => [ 'id' => $user->id ] ]);

            return response()->json([
                'error'   => true,
                'message' => 'Your account is unavailable at this moment.',
            ], 401);
        }

        event(new UserLoginEvent($user));

        return response()->json([
            'error' => false,
            'data'  => $this->prepareTokenData($token),
        ], 200);
    }

    public function refreshToken () {
        $user = auth()->user();

        $token = auth()->fromUser($user);
        event(new TokenRefreshEvent($user));

        // run `auth()->invalidate(true);` if you want to invalidate the token
        return response()->json([
            'error' => false,
            'data'  => $this->prepareTokenData($token),
        ]);
    }
}
