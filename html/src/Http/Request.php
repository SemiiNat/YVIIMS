<?php

namespace App\Http;

class Request
{
    /**
     * Get the request body parameters.
     *
     * @return array The request body parameters.
     */
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

    /**
     * Get the uploaded files.
     *
     * @return array The uploaded files.
     */
    public function getFiles(): array
    {
        return $_FILES;
    }
}
