<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Minimal front-router.
 *
 * Supports GET routes with {named} parameters.
 * Example:
 *   $router->get('/offers/{id}', [OfferController::class, 'show']);
 */
class Router
{
    /** @var array<int, array{string, string, array{string, string}}> */
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->routes[] = ['GET', $path, $handler];
    }

    public function dispatch(string $method, string $uri): void
    {
        // Normalise: strip query string and trailing slash (keep bare /)
        $uri = rtrim(parse_url($uri, PHP_URL_PATH), '/') ?: '/';

        foreach ($this->routes as [$routeMethod, $pattern, $handler]) {
            if ($routeMethod !== $method) {
                continue;
            }

            // Convert {param} placeholders → named capture groups
            $regex = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '(?P<$1>[^/]+)', $pattern);
            $regex = '#^' . $regex . '$#u';

            if (preg_match($regex, $uri, $matches) === 1) {
                // Keep only string-keyed matches (named captures)
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                [$class, $action] = $handler;
                $view       = new View();
                $controller = new $class($view);
                $controller->$action($params);
                return;
            }
        }

        // No match → 404
        http_response_code(404);
        echo '<!DOCTYPE html><html><head><title>404 Not Found</title></head>'
           . '<body><h1>404 — Page not found</h1>'
           . '<p><a href="/">Return home</a></p></body></html>';
    }
}
