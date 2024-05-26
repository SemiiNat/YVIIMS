<?php

namespace App\Helper;

class Container
{
    protected static $instances = [];
    protected static $services = [];

    public static function get($className)
    {
        if (!isset(self::$instances[$className])) {
            if (isset(self::$services[$className])) {
                self::$instances[$className] = call_user_func(self::$services[$className]);
            } else {
                self::$instances[$className] = self::resolve($className);
            }
        }

        return self::$instances[$className];
    }

    public static function register($className, callable $factory = null)
    {
        if ($factory !== null) {
            self::$services[$className] = $factory;
        } else {
            self::$services[$className] = function () use ($className) {
                return self::resolve($className);
            };
        }
    }

    private static function resolve($className)
    {
        $reflector = new \ReflectionClass($className);

        if (!$reflector->isInstantiable()) {
            throw new \Exception("Class $className is not instantiable");
        }

        $constructor = $reflector->getConstructor();

        if ($constructor === null) {
            return new $className;
        }

        $parameters = $constructor->getParameters();
        $dependencies = self::resolveDependencies($parameters);

        return $reflector->newInstanceArgs($dependencies);
    }

    private static function resolveDependencies($parameters)
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $dependencyClass = $parameter->getClass();

            if ($dependencyClass !== null) {
                $dependency = self::get($dependencyClass->getName());
                $dependencies[] = $dependency;
            } else {
                throw new \Exception("Unable to resolve dependency for parameter: " . $parameter->getName());
            }
        }

        return $dependencies;
    }
}
