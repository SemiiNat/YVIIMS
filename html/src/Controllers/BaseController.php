<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

abstract class BaseController
{
    protected $request;
    protected $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
}
