<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/container.php';

use App\Routes\Route;
use App\Routes\RouteCollection;
use App\Routes\Router;
use App\Middleware\AuthMiddleware;
use App\Controllers\AuthController;
use App\Controllers\CategoryController;
use App\Controllers\DashboardController;
use App\Controllers\ProductController;
use App\Controllers\SupplierController;

$routes = new RouteCollection();
$router = new Router($routes, $container);

$router->addMiddleware($container->get(AuthMiddleware::class));
$routes->add(new Route('GET', '/dashboard', [DashboardController::class, 'index']));
$routes->add(new Route('GET', '/login', [AuthController::class, 'get']));
$routes->add(new Route('POST', '/login', [AuthController::class, 'login']));
$routes->add(new Route('POST', '/logout', [AuthController::class, 'logout']));

// Product controller
$routes->add(new Route('GET', '/product', [ProductController::class, 'index']));
$routes->add(new Route('GET', '/product/create', [ProductController::class, 'create']));
$routes->add(new Route('POST', '/product', [ProductController::class, 'save']));

// Category controller
$routes->add(new Route('POST', '/category', [CategoryController::class, 'save']));
$routes->add(new Route('GET', '/category', [CategoryController::class, 'index']));
$routes->add(new Route('GET', '/api/category', [CategoryController::class, 'get']));
$routes->add(new Route('GET', '/category/{id}', [CategoryController::class, 'getById']));
$routes->add(new Route('DELETE', '/category/{id}', [CategoryController::class, 'delete']));

// Supplier controller
$routes->add(new Route('PUT', '/supplier/{id}', [SupplierController::class, 'update']));
$routes->add(new Route('POST', '/supplier', [SupplierController::class, 'save']));
$routes->add(new Route('GET', '/supplier', [SupplierController::class, 'index']));
$routes->add(new Route('GET', '/api/supplier', [SupplierController::class, 'get']));
$routes->add(new Route('GET', '/supplier/{id}', [SupplierController::class, 'getById']));
$routes->add(new Route('DELETE', '/supplier/{id}', [SupplierController::class, 'delete']));

$router->run();
