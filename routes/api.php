<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->group([ 'prefix' => 'auth' ], function ($router) {
    /** @var \Laravel\Lumen\Routing\Router $router */
    $router->post('create-account', 'AuthController@createAccount');
    $router->post('login', 'AuthController@loginToAccount');

    $router->group([ 'middleware' => [ 'auth' ] ], function ($router) {
        $router->post('refresh-token', 'AuthController@refreshToken');
    });
});

$router->get('cors', function () use ($router) {
    return [ 'error' => false, 'message' => 'functioning okay!' ];
});
