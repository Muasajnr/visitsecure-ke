<?php
/**
 * app/core/Router.php
 *
 * Minimal routing engine. Maps clean URLs (e.g. /login, /gate/scan)
 * to controller files, without exposing .php in the browser.
 */

class Router
{
    private array $routes = [];

    public function get(string $path, string $controllerFile, array $middleware = []): void
    {
        $this->add('GET', $path, $controllerFile, $middleware);
    }

    public function post(string $path, string $controllerFile, array $middleware = []): void
    {
        $this->add('POST', $path, $controllerFile, $middleware);
    }

    public function any(string $path, string $controllerFile, array $middleware = []): void
    {
        $this->add('GET', $path, $controllerFile, $middleware);
        $this->add('POST', $path, $controllerFile, $middleware);
    }

    private function add(string $method, string $path, string $controllerFile, array $middleware): void
    {
        $path = '/' . trim($path, '/');
        $pattern = preg_replace('#\{([a-zA-Z_]+)\}#', '(?P<$1>[^/]+)', $path);
        $this->routes[] = [
            'method'     => $method,
            'pattern'    => '#^' . $pattern . '$#',
            'controller' => $controllerFile,
            'middleware' => $middleware,
        ];
    }

    public function dispatch(string $requestUri, string $method): void
    {
        $path = parse_url($requestUri, PHP_URL_PATH);
        $path = '/' . trim($path, '/');
        if ($path === '') $path = '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) continue;
            if (preg_match($route['pattern'], $path, $matches)) {
                $params = array_filter($matches, fn($k) => !is_int($k), ARRAY_FILTER_USE_KEY);

                foreach ($route['middleware'] as $mw) {
                    $mwFile = APP_PATH . '/middleware/' . $mw . '.php';
                    if (file_exists($mwFile)) {
                        $allowed = require $mwFile;
                        if ($allowed === false) {
                            return; // middleware handles its own redirect/exit
                        }
                    }
                }

                $controllerFile = APP_PATH . '/controllers/' . $route['controller'];
                if (file_exists($controllerFile)) {
                    extract($params);
                    require $controllerFile;
                    return;
                }
            }
        }

        http_response_code(404);
        require VIEWS_PATH . '/errors/404.php';
    }
}
