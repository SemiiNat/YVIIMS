<?php

namespace App\Middleware;

interface MiddlewareInterface
{
    public function handle(): bool;
    public function process(): void;
}
