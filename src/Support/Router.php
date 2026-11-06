<?php

declare(strict_types=1);

namespace Doppel\Support;

/** Roteador mínimo: padrões como /noticias/{id} viram parâmetros do controller. */
final class Router
{
    /** @var array<int, array{string, string, array{class-string, string}}> */
    private array $routes = [];

    public function get(string $pattern, array $handler): void
    {
        $this->routes[] = ['GET', $pattern, $handler];
    }

    public function post(string $pattern, array $handler): void
    {
        $this->routes[] = ['POST', $pattern, $handler];
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = rtrim(parse_url($uri, PHP_URL_PATH) ?: '/', '/') ?: '/';

        foreach ($this->routes as [$routeMethod, $pattern, [$class, $action]]) {
            $regex = '#^' . preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $pattern) . '$#';
            if ($routeMethod !== $method || !preg_match($regex, $path, $matches)) {
                continue;
            }
            if ($method === 'POST') {
                verify_csrf();
            }
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            (new $class())->$action(...$params);
            return;
        }
        View::notFound();
    }
}
