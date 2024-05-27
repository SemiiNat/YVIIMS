<?php

namespace App\Http;

class Redirect
{
    /**
     * Redirect to the specified URL if not already on that URL.
     *
     * @param string $url The URL to redirect to.
     * @param array $params Optional query parameters.
     * @return void
     */
    public static function to(string $url, array $params = []): void
    {
        $currentUrl = $_SERVER['REQUEST_URI'];
        if ($currentUrl === $url) {
            return;
        }

        $queryString = http_build_query($params);
        $redirectUrl = $url . ($queryString ? '?' . $queryString : '');

        header("Location: $redirectUrl");
        exit();
    }
}
