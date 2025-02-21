<?php

namespace Core;

use Exception;
use ReflectionException;
use ReflectionMethod;

class Router
{
    public function __construct(
        protected Container $container
    )
    {}
    protected array $routes = [];

    public function add($method, $uri, $controller): static
    {
        $controller = is_string($controller) ? [$controller, '__invoke'] : $controller;

        if (str_contains($uri, '{')) {
            $this->routes[$method]['dynamic'][] = compact('uri', 'controller');
        } else {
            $this->routes[$method]['static'][$uri] = $controller;
        }

        return $this;
    }

    /**
     * @param string $uri
     * @param string $method
     * @throws ReflectionException
     */
    public function route(string $uri, string $method): void
    {
        // Check static routes first
        if (isset($this->routes[$method]['static'][$uri])) {
            $this->callAction($this->routes[$method]['static'][$uri]);
            return;
        }

        // Check dynamic routes
        foreach ($this->routes[$method]['dynamic'] as $route) {
            $pattern = $route['uri'];
            preg_match_all('/\{(\w+)\}/', $pattern, $paramNames);
            $paramNames = $paramNames[1];

            $regex = "#^" . preg_replace('/\{(\w+)\}/', '([^/]+)', $pattern) . "$#";

            if (preg_match($regex, $uri, $matches)) {
                array_shift($matches);
                $params = array_combine($paramNames, $matches);
                $this->callAction($route['controller'], $params);
                return;
            }
        }

        abort('No matching route found');
    }

    /**
     * @param $action
     * @param array $params
     * @throws ReflectionException
     * @throws Exception
     */
    protected function callAction($action, array $params = []): void
    {
        $class = $action[0];
        $method = $action[1];

        $reflection = new ReflectionMethod($class, $method);
        $args = [];

        foreach($reflection->getParameters() as $parameter){
            $name = $parameter->getName();
            if (isset($params[$name])) {
                $args[] = $params[$name];
            }
        }

        call_user_func_array([$this->container->get($class), $method], $args);
    }

    public function get(string $uri, array|string $controller): static
    {
        return $this->add('GET', $uri, $controller);
    }

    public function post(string $uri, array|string $controller): static
    {
        return $this->add('POST', $uri, $controller);
    }

    public function delete(string $uri, array|string $controller): static
    {
        return $this->add('DELETE', $uri, $controller);
    }

    public function patch(string $uri, array|string $controller): static
    {
        return $this->add('PATCH', $uri, $controller);
    }

    public function put(string $uri, array|string $controller): static
    {
        return $this->add('PUT', $uri, $controller);
    }

    function previousUrl()
    {
        return $_SERVER['HTTP_REFERER'];
    }
}
