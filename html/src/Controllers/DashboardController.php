<?php

namespace App\Controllers;

use App\Http\View;

class DashboardController
{
    public function index(): View
    {
        return View::make('dashboard');
    }
}
