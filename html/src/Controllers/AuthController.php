<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

class AuthController
{
    public function get(Request $request, Response $response): string
    {
        return 'login';
    }

    public function login(Request $request, Response $response)
    {
        // Handle login logic here
    }

    public function register(): string
    {
        return 'register';
    }
}
