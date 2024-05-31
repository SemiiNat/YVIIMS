<?php

use App\Helper\Container;
use App\Helper\DatabaseHelper;
use App\Controllers\AuthController;
use App\Controllers\CategoryController;
use App\Controllers\DashboardController;
use App\Controllers\ProductController;
use App\Controllers\SupplierController;
use App\Services\AuthService;
use App\Helper\EnvHelper;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Services\SupplierService;

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

$container->register(ProductController::class, function () use ($container) {
    $productService = $container->get(ProductService::class);
    $categoryService = $container->get(CategoryService::class);
    return new ProductController($productService, $categoryService);
});

$container->register(CategoryController::class, function () use ($container) {
    $categoryService = $container->get(CategoryService::class);
    return new CategoryController($categoryService);
});

$container->register(SupplierController::class, function () use ($container) {
    $supplierService = $container->get(SupplierService::class);
    return new SupplierController($supplierService);
});

return $container;
