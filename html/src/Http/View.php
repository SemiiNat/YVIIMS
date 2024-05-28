<?php

namespace App\Http;

/**
 * Class View
 *
 * Handles the rendering of views with optional data, partials, and scripts.
 */
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
     * Partials to be included.
     *
     * @var array
     */
    protected static $partials = [];

    /**
     * Scripts to be included.
     *
     * @var array
     */
    protected static $scripts = [];

    /**
     * Sections to be rendered.
     *
     * @var array
     */
    protected static $sections = [];

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
        $content = ob_get_clean();

        $content .= $this->renderPartials();
        $content .= $this->renderScripts();

        return $content;
    }

    /**
     * Include a partial view.
     *
     * @param string $partial The partial view file path.
     * @param array $data The data to be passed to the partial view.
     * @throws \Exception If the partial view file is not found.
     */
    public static function includePartial(string $partial, array $data = []): void
    {
        self::$partials[] = ['partial' => $partial, 'data' => $data];
    }

    /**
     * Render all included partials.
     *
     * @return string The rendered partials output.
     * @throws \Exception If a partial view file is not found.
     */
    protected function renderPartials(): string
    {
        $output = '';

        foreach (self::$partials as $partial) {
            $partialPath = __DIR__ . '/../Views/partials/' . str_replace('.', '/', $partial['partial']) . '.php';

            if (file_exists($partialPath)) {
                extract($partial['data']);
                ob_start();
                include $partialPath;
                $output .= ob_get_clean();
            } else {
                throw new \Exception("Partial file not found: " . $partialPath);
            }
        }

        return $output;
    }

    /**
     * Add a script file to the view.
     *
     * @param string $script The script file path.
     */
    public static function addScript(string $script): void
    {
        self::$scripts[] = $script;
    }

    /**
     * Render all added scripts.
     *
     * @return string The rendered script tags.
     * @throws \Exception If a script file is not found.
     */
    protected function renderScripts(): string
    {
        $scripts = '';

        foreach (self::$scripts as $script) {
            $scriptPath = __DIR__ . '/../Views/scripts/' . str_replace('.', '/', $script) . '.php';

            if (file_exists($scriptPath)) {
                ob_start();
                include $scriptPath;
                $scripts .= ob_get_clean();
            } else {
                throw new \Exception("Script file not found: " . $scriptPath);
            }
        }

        return $scripts;
    }

    /**
     * Start a section for dynamic content.
     *
     * @param string $name The section name.
     */
    public static function startSection(string $name): void
    {
        ob_start();
        self::$sections[$name] = '';
    }

    /**
     * End a section for dynamic content.
     *
     * @param string $name The section name.
     */
    public static function endSection(string $name): void
    {
        self::$sections[$name] = ob_get_clean();
    }

    /**
     * Render a section's content.
     *
     * @param string $name The section name.
     */
    public static function renderSection(string $name): void
    {
        if (isset(self::$sections[$name])) {
            echo self::$sections[$name];
        }
    }
}
