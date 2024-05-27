<?php

use App\Helper\Container;
use App\Helper\DatabaseHelper;
use App\Controllers\AuthController;
use App\Services\AuthService;
use App\Helper\EnvHelper;

$container = new Container();
EnvHelper::load();

$container->register(DatabaseHelper::class, function () {
    return DatabaseHelper::getInstance();
});

$container->register(AuthController::class, function () use ($container) {
    $authService = $container->get(AuthService::class);
    return new AuthController($authService);
});

return $container;
