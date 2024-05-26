<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/container.php';

use App\Routes\Route;
use App\Routes\RouteCollection;
use App\Routes\Router;
use App\Controllers\AuthController;

$routes = new RouteCollection();

$routes->add(new Route('GET', '/login', [AuthController::class, 'get'], 'auth.login'));
$routes->add(new Route('POST', '/login', [AuthController::class, 'login'], 'auth.login'));

$router = new Router($routes, $container);

$router->run();
