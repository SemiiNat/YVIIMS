<?php

namespace App\Middleware;

use App\Http\Redirect;
use App\Http\Session;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(): bool
    {
        Session::start();
        if (!Session::has('user')) {
            return true;
        }
        return false;
    }

    public function process(): void
    {
        Redirect::to('/login');
    }
}
