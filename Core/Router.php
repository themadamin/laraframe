<?php

namespace Core;

use Exception;
use ReflectionMethod;

class Router
{
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
     * @throws Exception
     */
    public function route($uri, $method)
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

        // No route found
        abort('No matching route found');
    }

    /**
     * @throws \ReflectionException
     */
    protected function callAction($action, $params = []) {
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

        $reflection->invokeArgs(new $class, $args);
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
