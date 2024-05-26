<?php

namespace App\Routes;

class Route
{
    private string $method;
    private string $path;
    private array $action;

    public function __construct(string $method, string $path, array $action)
    {
        $this->method = $method;
        $this->path = $path;
        $this->action = $action;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getAction(): array
    {
        return $this->action;
    }
}
