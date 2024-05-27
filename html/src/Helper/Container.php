<?php

namespace App\Helper;

/**
 * The Container class provides a simple dependency injection container.
 */
class Container
{
    /**
     * @var array The instances of resolved classes.
     */
    protected static $instances = [];

    /**
     * @var array The registered services.
     */
    protected static $services = [];

    /**
     * Get an instance of the specified class.
     *
     * @param string $className The name of the class.
     * @return object The instance of the class.
     * @throws \Exception If the class is not instantiable.
     */
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

    /**
     * Register a class with an optional factory function.
     *
     * @param string $className The name of the class.
     * @param callable|null $factory The factory function to create the instance.
     * @return void
     */
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

    /**
     * Resolve the dependencies of a class and create an instance.
     *
     * @param string $className The name of the class.
     * @return object The instance of the class.
     * @throws \Exception If the class is not instantiable or if a dependency cannot be resolved.
     */
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

    /**
     * Resolve the dependencies of a constructor.
     *
     * @param array $parameters The parameters of the constructor.
     * @return array The resolved dependencies.
     * @throws \Exception If a dependency cannot be resolved.
     */
    private static function resolveDependencies($parameters)
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if ($type !== null && !$type->isBuiltin()) {
                $dependencyClassName = $type->getName();
                $dependency = self::get($dependencyClassName);
                $dependencies[] = $dependency;
            } else {
                throw new \Exception("Unable to resolve dependency for parameter: " . $parameter->getName());
            }
        }

        return $dependencies;
    }
}
