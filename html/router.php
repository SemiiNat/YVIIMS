<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/container.php';

use App\Routes\Route;
use App\Routes\RouteCollection;
use App\Routes\Router;
use App\Middleware\AuthMiddleware;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\ProductController;

$routes = new RouteCollection();
$router = new Router($routes, $container);

$router->addMiddleware($container->get(AuthMiddleware::class));
$routes->add(new Route('GET', '/dashboard', [DashboardController::class, 'index'], 'dashboard.index'));
$routes->add(new Route('GET', '/login', [AuthController::class, 'get'], 'auth.login'));
$routes->add(new Route('POST', '/login', [AuthController::class, 'login'], 'auth.login'));
$routes->add(new Route('POST', '/logout', [AuthController::class, 'logout'], 'auth.logout'));

// TODO: add protected user based routes

// product controller
$routes->add(new Route('GET','/product',[ProductController::class,'index'],'product.index'));

$router->run();
