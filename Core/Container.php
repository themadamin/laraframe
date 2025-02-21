<?php

namespace Core;

use Core\Exceptions\ContainerException;
use Exception;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;

class Container implements ContainerInterface
{
    /**
     * @var array
     */
    protected array $bindings = [];

    /**
     * @param string $id
     * @return mixed
     * @throws Exception
     */
    public function get(string $id): mixed
    {
        if ($this->has($id)){
            $entry = $this->bindings[$id];

            if (is_callable($entry)){
                return $entry($this);
            }

            $id = $entry;
        }

        return $this->resolve($id);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->bindings[$id]);
    }

    /**
     * @param string $id
     * @param callable|string $concrete
     * @return void
     */
    public function bind(string $id, callable|string $concrete): void
    {
        $this->bindings[$id] = $concrete;
    }

    /**
     * @throws Exception
     */
    public function resolve($id)
    {
        // Reflect class
        $reflection = new ReflectionClass($id);

        // Check if class instantiable
        if (! $reflection->isInstantiable()){
            throw new ContainerException("Class $id is not instantiable!");
        }

        // Check if reflected class have dependencies
        $constructor = $reflection->getConstructor();

        // If class has no constructor we instantiate it
        if (! $constructor){
            return new $id;
        }

        // If class has constructor we should check for parameters
        $parameters = $constructor->getParameters();

        // If constructor have no parameters we instantiate it
        if (! $parameters){
            return new $id;
        }

        // Check if parameter is a class
        $dependencies = array_map(function (ReflectionParameter $parameter) use ($id){
            $name = $parameter->getName();
            $type = $parameter->getType();

            // Check if parameter has type hint
            if(! $type){
                throw new ContainerException("Failed to resolve class $id because parameter $name missing a type hint");
            }

            // Check if type is not union
            if ($type instanceof ReflectionUnionType){
                throw new ContainerException("Failed to resolve class $id because parameter $name is union type");
            }

            // Check if parameter type is class and not built in type
            if ($type instanceof ReflectionNamedType && ! $type->isBuiltin()){
                // Loop through the same stage classes
                return $this->get($type->getName());
            }

            // Throw exception if parameter invalid or doesn't match any condition above
            throw new ContainerException("Failed to resolve class $id because invalid parameter $name");

        }, $parameters);
        // Create new instance of class with all dependencies
        return $reflection->newInstanceArgs($dependencies);
    }

}