<?php

namespace App\Routes;

use App\Middleware\MiddlewareInterface;
use App\Http\Request;
use App\Http\Response;
use App\Http\View;

/**
 * Class Router
 *
 * Handles routing and request handling for the application.
 */
class Router
{
    private RouteCollection $routes;
    private array $middleware = [];

    private $container;

    /**
     * Router constructor.
     *
     * @param RouteCollection $routes The collection of routes.
     * @param mixed $container The dependency injection container.
     */
    public function __construct(RouteCollection $routes, $container)
    {
        $this->routes = $routes;
        $this->container = $container;
    }

    /**
     * Adds a middleware to be applied to the routes.
     *
     * @param MiddlewareInterface $middleware The middleware to be added.
     */
    public function addMiddleware(MiddlewareInterface $middleware): void
    {
        $this->middleware[] = $middleware;
    }

    /**
     * Runs the router and handles the incoming request.
     * If a matching route is found, it handles the route.
     * If no matching route is found, it sends a 404 response.
     */
    public function run(): void
    {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
    
        foreach ($this->routes->getRoutes() as $route) {
            $params = [];
            if ($route->getMethod() === $method && $this->matchRoute($route->getPath(), $uri, $params)) {
                $this->handleRoute($route, $params);
                return;
            }
        }
    
        $response = new Response('404 Not Found', 404);
        $response->send();
    }
    

    /**
     * Matches the given URI against the route pattern.
     *
     * @param string $pattern The route pattern.
     * @param string $uri The URI to match against.
     * @return bool Returns true if the URI matches the pattern, false otherwise.
     */
    private function matchRoute(string $pattern, string $uri, &$params = []): bool
    {
        $uriPath = parse_url($uri, PHP_URL_PATH);
    
        $pattern = preg_replace('/\{([A-Za-z]+)\}/', '(?P<\1>[A-Za-z0-9]+)', $pattern);
        $pattern = str_replace('/', '\/', $pattern);
    
        if (preg_match('/^' . $pattern . '$/', $uriPath, $matches)) {
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $params[$key] = $value;
                }
            }
            return true;
        }
    
        return false;
    }
    


    /**
     * Handles the matched route by invoking the corresponding controller action.
     *
     * @param Route $route The matched route.
     */
    private function handleRoute(Route $route, array $params = []): void
    {
        $controllerName = $route->getAction()[0];
        $actionName = $route->getAction()[1];
    
        if (class_exists($controllerName)) {
            $controller = $this->container->get($controllerName);
    
            // Apply middleware
            foreach ($this->middleware as $middleware) {
                if ($middleware->handle()) {
                    $middleware->process();
                }
            }
    
            if (method_exists($controller, $actionName)) {
                $request = new Request();
                $response = new Response();
    
                // Call the controller action with parameters
                $result = $controller->$actionName($request, $response, ...$params);
    
                // Check if the result is an instance of View and render it
                if ($result instanceof View) {
                    $this->renderView($result, $response);
                } else {
                    $response->send();
                }
    
                return;
            }
        }
    
        $response = new Response('404 Not Found', 404);
        $response->send();
    }    

    /**
     * Renders the specified view.
     *
     * @param View $view The View object.
     * @param Response $response The response object.
     */
    private function renderView(View $view, Response $response): void
    {
        $content = $view->render();
        $response->setBody($content);
        $response->send();
    }
}
