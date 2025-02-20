<?php

namespace Core;

use Exception;
use ReflectionClass;
use ReflectionException;

class Container
{
//    protected array $bindings = [];  // Store class bindings
//    protected array $instances = []; // Store singleton instances
//
//    /**
//     * Bind an interface or class to a concrete implementation.
//     */
//    public function bind(string $abstract, callable|string $concrete)
//    {
//        $this->bindings[$abstract] = $concrete;
//    }
//
//    /**
//     * Bind a singleton instance.
//     */
//    public function singleton(string $abstract, callable|string $concrete)
//    {
//        $this->bindings[$abstract] = $concrete;
//        $this->instances[$abstract] = null; // Lazy initialization
//    }
//
//    /**
//     * Resolve a class or interface.
//     */
//    public function resolve(string $abstract)
//    {
//        // Check for a singleton instance
//        if (isset($this->instances[$abstract]) && $this->instances[$abstract] !== null) {
//            return $this->instances[$abstract];
//        }
//
//        // Check if it's bound
//        if (isset($this->bindings[$abstract])) {
//            $concrete = $this->bindings[$abstract];
//
//            // If a callable is provided
//            if (is_callable($concrete)) {
//                $object = $concrete($this);
//            } else {
//                $object = $this->resolve($concrete);
//            }
//        } else {
//            // Autowiring: Handle unregistered classes
//            $object = $this->autowire($abstract);
//        }
//
//        // If it's a singleton, store the instance
//        if (array_key_exists($abstract, $this->instances)) {
//            $this->instances[$abstract] = $object;
//        }
//
//        return $object;
//    }
//
//    /**
//     * Automatically resolve class dependencies using reflection.
//     * @throws ReflectionException
//     * @throws Exception
//     */
//    protected function autowire(string $abstract)
//    {
//        $reflector = new ReflectionClass($abstract);
//
//        // Check if the class is instantiable
//        if (!$reflector->isInstantiable()) {
//            throw new Exception("Class {$abstract} is not instantiable.");
//        }
//
//        // Get the constructor (if any)
//        $constructor = $reflector->getConstructor();
//        if (!$constructor) {
//            return new $abstract; // No dependencies
//        }
//
//        // Resolve dependencies recursively
//        $parameters = $constructor->getParameters();
//        $dependencies = array_map(function ($parameter) {
//            $type = $parameter->getType();
//            if (!$type) {
//                throw new Exception("Cannot resolve the parameter {$parameter->name}.");
//            }
//
//            return $this->resolve($type->getName());
//        }, $parameters);
//
//        return $reflector->newInstanceArgs($dependencies);
//    }

    protected $bindings = [];

    public function bind($key, $resolver)
    {
        $this->bindings[$key] = $resolver;
    }

    /**
     * @throws Exception
     */
    public function resolve($key)
    {
        var_dump($key);
        if (!array_key_exists($key, $this->bindings)) {
            throw new Exception("No matching binding found for {$key}");
        }

        $resolver = $this->bindings[$key];

        return call_user_func($resolver);
    }
}