<?php

namespace Core;

use Core\Middleware\Middleware;
use Exception;

class Router
{
    protected array $routes = [];

    public function add($method, $uri, $controller)
    {
        $this->routes[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => $method,
            'middleware' => null
        ];

        return $this;
    }

    public function get($uri, $controller): static
    {
        return $this->add('GET', $uri, $controller);
    }

    public function post($uri, $controller): static
    {
        return $this->add('POST', $uri, $controller);
    }

    public function delete($uri, $controller): static
    {
        return $this->add('DELETE', $uri, $controller);
    }

    public function patch($uri, $controller): static
    {
        return $this->add('PATCH', $uri, $controller);
    }

    public function put($uri, $controller): static
    {
        return $this->add('PUT', $uri, $controller);
    }

    public function only($key): static
    {
        $this->routes[array_key_last($this->routes)]['middleware'] = $key;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function route($uri, $method)
    {
        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === strtoupper($method)) {
                if ($route['middleware'] ?? false) {
                    Middleware::resolve($route['middleware']);
                }
                return require base_path('Http/controllers/'.$route['controller']);
            }
        }

        $this->abort();
    }

    protected function abort($code = 404)
    {
        http_response_code($code);

        require base_path("views/{$code}.php");

        die();
    }


    function previousUrl()
    {
        return $_SERVER['HTTP_REFERER'];
    }
}
