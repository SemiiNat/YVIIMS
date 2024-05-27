<?php

namespace App\Http;

class View
{
    protected $view;
    protected $data;

    public function __construct(string $view, array $data = [])
    {
        $this->view = $view;
        $this->data = $data;
    }

    public static function make(string $view, array $data = []): self
    {
        return new self($view, $data);
    }

    public function render(): string
    {
        extract($this->data);

        $viewPath = __DIR__ . '/../Views/' . str_replace('.', '/', $this->view) . '.php';

        if (!file_exists($viewPath)) {
            throw new \Exception("View file not found: " . $viewPath);
        }

        ob_start();
        include $viewPath;
        return ob_get_clean();
    }
}
