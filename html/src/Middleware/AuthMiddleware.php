<?php

namespace App\Middleware;

use App\Http\Redirect;
use App\Http\Session;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(): bool
    {
        Session::start();

        // Check if user is authenticated
        if (!Session::has('user') && !$this->isPublicRoute()) {
            return true; // Trigger the middleware process to handle redirect
        }

        return false; // No need to process the middleware, user is authenticated or accessing public route
    }

    public function process(): void
    {
        // Redirect to login page if user is not authenticated
        Redirect::to('/login');
    }

    /**
     * Check if the current route is public.
     *
     * @return bool True if the route is public, false otherwise.
     */
    private function isPublicRoute(): bool
    {
        $publicRoutes = [
            '/login',
        ];

        $currentRoute = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        return in_array($currentRoute, $publicRoutes);
    }
}
