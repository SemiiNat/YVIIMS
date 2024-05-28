<?php

use App\Helper\Container;
use App\Helper\DatabaseHelper;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\ProductController;
use App\Services\AuthService;
use App\Helper\EnvHelper;
use App\Services\ProductService;

$container = new Container();
EnvHelper::load();

$container->register(DatabaseHelper::class, function () {
    return DatabaseHelper::getInstance();
});

$container->register(AuthController::class, function () use ($container) {
    $authService = $container->get(AuthService::class);
    return new AuthController($authService);
});

$container->register(DashboardController::class, function () {
    return new DashboardController();
});

$container->register(ProductController::class, function() use ($container) {
    $productService = $container->get(ProductService::class);
    return new ProductController($productService);
});

return $container;
