<?php

namespace App\Http;

class Request
{
    public function getBody(): array
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return $_GET;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $_POST;
        }

        // add other methods as necessary...

        return [];
    }

    public function getFiles(): array
    {
        return $_FILES;
    }

    // You can add other methods as necessary...
}
