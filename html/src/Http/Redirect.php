<?php

namespace App\Http;

class Redirect
{
    /**
     * Redirect to the specified URL if not already on that URL and the request method matches.
     *
     * @param string $url The URL to redirect to.
     * @param string $method The HTTP request method (GET, POST, etc.).
     * @param array $params Optional query parameters.
     * @return void
     */
    public static function to(string $url, string $method = 'GET', array $params = []): void
    {
        $currentUrl = $_SERVER['REQUEST_URI'];
        $currentMethod = $_SERVER['REQUEST_METHOD'];

        if ($currentUrl === $url && $currentMethod === $method) {
            return;
        }

        $queryString = http_build_query($params);
        $redirectUrl = $url . ($queryString ? '?' . $queryString : '');

        header("Location: $redirectUrl");
        exit();
    }
}
