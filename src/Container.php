<?php

namespace Idx;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionParameter;

class Container
{

    /** @var Container The shared available container. */
    private static $container;

    /** @var array The alias types or Closure */
    private $alias = [];

    /** @var array The shared instances */
    private $instances = [];

    public function __construct()
    {
        if (!is_null(self::$container)) {
            return self::$container;
        }

        $this->alias = [
            'Client' => '\GuzzleHttp\Client',
            'Config' => '\Idx\Config',
            'Idx\Token\TokenInterface' => function () {
                return \Idx\Token\TokenFactory::createToken();
            },
            'Listing' => '\Idx\Resource\Listing',
            'Image' => '\Idx\Resource\Image',
            'Agent' => '\Idx\Resource\Agent',
            'Office' => '\Idx\Resource\Office',
            'MlsInfo' => '\Idx\Resource\MlsInfo',
        ];
    }

    /**
     * Get container instance.
     *
     * @return Idx\Container
     */
    public static function getInstance()
    {
        if (is_null(static::$container)) {
            static::$container = new static;
        }

        return static::$container;
    }

    /**
     * * Get the given type instance from the container
     *
     * @param string $instanceName
     * @return mixed
     */
    public function get(string $instanceName)
    {
        if (!isset($this->instances[$instanceName])) {
          $this->instances[$instanceName] = $this->build($instanceName);
        }
        return $this->instances[$instanceName];
    }


    /**
     * Build the given type instance
     *
     * @param string $instance
     * @return mixed
     */
    private function build(string $instance)
    {
        if (isset($this->alias[$instance])) {
            $instance = $this->alias[$instance];
        }
        if ($instance instanceOf Closure) {
            return $instance();
        }
        $reflector = new ReflectionClass($instance);
        $constructor = $reflector->getConstructor();
        $dependencies = $constructor->getParameters();

        if (count($dependencies)) {
            return $reflector->newInstanceArgs($this->resolveDependencies($dependencies));
        }
        return $reflector->newInstance();
    }

    /**
     * Resolve all of the dependencies for creating instance.
     *
     * @param array $dependencies
     * @return array
     */
    private function resolveDependencies(array $dependencies): array
    {
        $results = [];

        foreach ($dependencies as $dependence) {
            $results[] = is_null($dependence->getClass())
                ? $this->resolvePrimitive($dependence)
                : $this->build($dependence->getClass()->name);
        }

        return $results;
    }

    /**
     * Resolve a non-class hinted primitive dependence for creating instance.
     *
     * @param  \ReflectionParameter  $parameter
     * @return mixed
     */
    private function resolvePrimitive(ReflectionParameter $dependence)
    {
        if ($dependence->isOptional()) {
            return $dependence->getDefaultValue();
        }
        $config = $this->get('Config');
        if (is_null($value = $config->get($dependence->name))) {
            throw new Exception(
                "Unresolvable resolving class {$parameter->getDeclaringClass()->getName()} dependence: [$parameter]"
            );
        }
        return $value;
    }
}
