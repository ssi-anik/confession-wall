<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('cors', function () use ($router) {
    return [ 'error' => false, 'message' => 'functioning okay!' ];
});
