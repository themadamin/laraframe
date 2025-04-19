<?php

namespace Core;

use Core\Middlewares\Middleware;
use Exception;
use ReflectionMethod;

class Router
{
    protected Request $request;

    public function __construct(protected Container $container)
    {
        $this->request = $this->container->get(Request::class);
    }
    protected array $routes = [];

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

    public function middleware(array|string $middlewares): static
    {
        $middlewares = is_string($middlewares) ? [$middlewares] : $middlewares;
        $this->routes[array_key_last($this->routes)]['middleware'] = $middlewares;

        return $this;
    }

    public function add($method, $uri, $action): static
    {
        $action = is_string($action) ? [$action, '__invoke'] : $action;

        $route = ['uri' => $uri, 'method' => $method, 'action' => $action, 'dynamic' => false, 'middleware' => []];

        if (str_contains($uri, '{')) {
            $route['dynamic'] = true;
        }

        $this->routes[] = $route;

        return $this;
    }

    public function route(string $uri, string $method): void
    {
        [$static, $dynamic] = $this->sortIntoGroups($method);

        // Check static routes first
        if ($this->checkStatic($static, $uri, $method)){
            return;
        }

        // Check dynamic routes
        if ($this->checkDynamic($dynamic, $uri, $method)){
            return;
        }

        abort('No matching route found');
    }

    public function sortIntoGroups(string $method): array
    {
        $static = [];
        $dynamic = [];

        // Sort routes into groups
        foreach ($this->routes as $route){
            if(!$route['dynamic'] && $route['method'] === $method){
                $static[] = $route;
            }
            else {
                $dynamic[] = $route;
            }
        }

        return [$static, $dynamic];
    }

    public function checkStatic(array $static, string $uri, string $method): bool
    {
        foreach ($static as $route) {
            if ($route['uri'] === $uri && $route['method'] === $method) {
                $this->callAction($route);
                return true;
            }
        }
        return false;
    }

    public function checkDynamic(array $dynamic, string $uri, string $method): bool
    {
        foreach ($dynamic as $route) {
            $pattern = $route['uri'];
            preg_match_all('/\{(\w+)\}/', $pattern, $paramNames);
            $paramNames = $paramNames[1];

            $regex = "#^" . preg_replace('/\{(\w+)\}/', '([^/]+)', $pattern) . "$#";

            if (preg_match($regex, $uri, $matches) && $route['method'] === $method) {
                array_shift($matches);
                $params = array_combine($paramNames, $matches);
                $this->callAction($route, $params);
                return true;
            }
        }
        return false;
    }

    protected function callAction(array $route, array $params = []): void
    {
        $class = $route['action'][0];
        $method = $route['action'][1];

        $reflection = new ReflectionMethod($class, $method);
        $args = [];

        foreach($reflection->getParameters() as $parameter){
            $name = $parameter->getName();
            if (isset($params[$name])) {
                $args[] = $params[$name];
            }
            if ($name === 'request'){
                $args['request'] = $this->request;
            }
        }

        // Check if route passes middlewares
        if (!empty($route['middleware'])){
            $this->checkMiddlewares($route['middleware']);
        }

        call_user_func_array([$this->container->get($class), $method], $args);
    }

    public function checkMiddlewares(array $middlewares): void
    {
        foreach($middlewares as $middleware){
            Middleware::resolve($middleware);
        }
    }

    public static function group(array $group, callable $callback)
    {
        //TODO recursive group assigning for routes
    }

    function previousUrl()
    {
        return $_SERVER['HTTP_REFERER'];
    }
}
