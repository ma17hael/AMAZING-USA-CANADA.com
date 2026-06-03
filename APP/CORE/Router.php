<?php
namespace App\Core;

class Router {
    private array $routes = [];
    private string $currentLang = 'fr';
    private string $currentPath = '/';
    private array $supportedLangs = ['fr', 'fr-ca', 'en', 'en-ca'];

    public function get(string $path, array $handler): void {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $uri, string $method, $translator, $router, $auth): void {
        $path = parse_url($uri, PHP_URL_PATH);

        $langPrefix = null;
        foreach ($this->supportedLangs as $lang) {
            $prefix = '/' . $lang;
            if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
                $langPrefix = $lang;
                $path = substr($path, strlen($prefix)) ?: '/';
                break;
            }
        }

        $this->currentPath = $path;
        $this->currentLang = $langPrefix ?? 'fr';

        $translator->detect(urlLang: $langPrefix, sessionLang: $_SESSION['lang'] ?? null);

        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $pattern = preg_replace('/\{(\w+)(:[^}]+)?\}/', '(?P<$1>[^/]+)', $route);
            if (preg_match("#^$pattern$#", $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                [$class, $method] = $handler;
                (new $class)->$method($params, $translator, $router, $auth);
                return;
            }
        }

        http_response_code(404);
        require_once __DIR__ . '/../../VIEWS/ERRORS/404.php';
    }

    public function getLang(): string {
        return $this->currentLang;
    }

    public function getCurrentPath(): string {
        return $this->currentPath;
    }
}