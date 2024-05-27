<?php

namespace App\Controllers;

use App\Http\View;
use App\Http\Request;
use App\Http\Response;

class DashboardController
{
    public function index(): View
    {
        return View::make('dashboard');
    }
}
