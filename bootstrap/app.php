<?php

require_once __DIR__ . '/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(dirname(__DIR__)))->bootstrap();

date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

$app = new Laravel\Lumen\Application(dirname(__DIR__));

// $app->withFacades();

$app->withEloquent();

$app->singleton(Illuminate\Contracts\Debug\ExceptionHandler::class, App\Exceptions\Handler::class);

$app->singleton(Illuminate\Contracts\Console\Kernel::class, App\Console\Kernel::class);

$app->configure('app');
$app->configure('auth');
$app->configure('queue');
$app->configure('filesystems');
$app->configure('cors');
$app->configure('lighthouse');
$app->configure('graphql-playground');

$app->middleware([
    Fruitcake\Cors\HandleCors::class,
]);

$app->routeMiddleware([
    'auth' => App\Http\Middleware\Authenticate::class,
]);

// $app->register(App\Providers\AppServiceProvider::class);
// $app->register(App\Providers\AuthServiceProvider::class);
$app->register(App\Providers\EventServiceProvider::class);
$app->register(App\Providers\ValidationServiceProvider::class);
$app->register(Illuminate\Redis\RedisServiceProvider::class);
$app->register(Fruitcake\Cors\CorsServiceProvider::class);
$app->register(Anik\Form\FormRequestServiceProvider::class);
$app->register(Tymon\JWTAuth\Providers\LumenServiceProvider::class);
$app->register(Illuminate\Filesystem\FilesystemServiceProvider::class);

$app->register(Nuwave\Lighthouse\LighthouseServiceProvider::class);
$app->register(Nuwave\Lighthouse\Pagination\PaginationServiceProvider::class);
$app->register(MLL\GraphQLPlayground\GraphQLPlaygroundServiceProvider::class);

if (is_local() || in_environment('staging')) {
    $app->register(Flipbox\LumenGenerator\LumenGeneratorServiceProvider::class);
}

if (log_db_queries()) {
    // uses terminate method, that's why singleton
    $app->singleton(App\Http\Middleware\QueryLoggerMiddleware::class);
    $app->middleware(App\Http\Middleware\QueryLoggerMiddleware::class);
}

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__ . '/../routes/web.php';
});

$app->router->group([
    'prefix'    => 'api',
    'namespace' => 'App\Http\Controllers\Api',
], function ($router) {
    require __DIR__ . '/../routes/api.php';
});

return $app;
