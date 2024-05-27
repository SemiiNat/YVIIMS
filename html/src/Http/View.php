<?php

namespace App\Http;

class View
{
    /**
     * The view file path.
     *
     * @var string
     */
    protected $view;

    /**
     * The data to be passed to the view.
     *
     * @var array
     */
    protected $data;

    /**
     * Create a new View instance.
     *
     * @param string $view The view file path.
     * @param array $data The data to be passed to the view.
     */
    public function __construct(string $view, array $data = [])
    {
        $this->view = $view;
        $this->data = $data;
    }

    /**
     * Create a new View instance.
     *
     * @param string $view The view file path.
     * @param array $data The data to be passed to the view.
     * @return self
     */
    public static function make(string $view, array $data = []): self
    {
        return new self($view, $data);
    }

    /**
     * Render the view and return the output.
     *
     * @return string The rendered view output.
     * @throws \Exception If the view file is not found.
     */
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
