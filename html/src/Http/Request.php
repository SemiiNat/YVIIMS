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
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === 'GET') {
            return $_GET;
        }

        if ($method === 'POST') {
            return $_POST;
        }

        if (in_array($method, ['PUT', 'DELETE', 'PATCH'])) {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            return $data ?: [];
        }

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
