<?php

namespace App\Controllers;

use App\Http\Redirect;
use App\Services\AuthService;
use App\Http\Request;
use App\Http\Session;
use App\Http\View;

class AuthController
{
    private $service;

    /**
     * AuthController constructor.
     *
     * @param AuthService $service The authentication service.
     */
    public function __construct(AuthService $service)
    {
        $this->service = $service;
        Session::start();
    }

    /**
     * Get the login view.
     *
     * @return View The login view.
     */
    public function get(): View
    {
        return View::make('login');
    }

    /**
     * Handle the login request.
     *
     * @param Request $request The login request.
     * @return Response|void
     */
    public function login(Request $request)
    {
        $data = $request->getBody();

        $result = $this->service
            ->authenticate($data["username"], $data["password"]);

        if ($result['user'] === null) {
            return View::make('login', ['error' => "Invalid username or password"]);
        }

        Session::put('user', $result['user']);
        Redirect::to('/dashboard');
    }

    /**
     * Handle the logout request.
     */
    public function logout(): void
    {
        Session::destroy();
        Redirect::to('/login');
    }
}
