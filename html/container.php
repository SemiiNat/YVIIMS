<?php

use App\Helper\Container;
use App\Helper\DatabaseHelper;
use App\Controllers\AuthController;
// Create a new container instance
$container = new Container();

$container->register(DatabaseHelper::class, function () {
    return DatabaseHelper::getInstance();
});

// Register UserService and its dependencies
// $container->register(UserService::class);

// Register AuthMiddleware
// $container->register(AuthMiddleware::class);

// Register UserController with UserService dependency

// $container->register(UserController::class, function () use ($container) {
//     $userService = $container->get(UserService::class);
//     return new UserController($userService);
// });

$container->register(AuthController::class, function () use ($container) {
    return new AuthController();
});

return $container;
