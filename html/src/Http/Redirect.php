<?php

namespace App\Http;

class Redirect
{
    public static function to(string $url, array $params = []): void
    {
        $queryString = http_build_query($params);
        $redirectUrl = $url . ($queryString ? '?' . $queryString : '');
        header("Location: $redirectUrl");
        exit();
    }
}
