<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->group([ 'prefix' => 'auth' ], function ($router) {
    $router->post('create-account', 'AuthController@createAccount');
    $router->post('login', 'AuthController@loginToAccount');

    $router->group([ 'middleware' => [ 'auth' ] ], function ($router) {
        $router->post('refresh-token', 'AuthController@refreshToken');
    });
});

$router->group([ 'middleware' => [ 'auth' ] ], function ($router) {
    $router->group([ 'prefix' => 'me' ], function ($router) {
        $router->get('settings', 'SettingsController@getUserSettings');
        $router->patch('settings', 'SettingsController@updateSettings');
    });
});

$router->get('user-info/{username}', 'ConfessionController@getUserInfo');
