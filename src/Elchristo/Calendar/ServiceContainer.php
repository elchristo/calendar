<?php

namespace Elchristo\Calendar;

use Interop\Container\ContainerInterface;
use Elchristo\Calendar\Service\Builder\CalendarBuilder;
use Elchristo\Calendar\Service\Builder\CalendarBuilderFactory;
use Elchristo\Calendar\Service\Builder\SourceBuilder;
use Elchristo\Calendar\Service\Builder\SourceBuilderFactory;

/**
 * Service container to locate internal calendar services
 */
class ServiceContainer implements ContainerInterface
{
    private static $instance = null;

    protected $config = null;
    protected $instances = [];
    protected $factories = [
        CalendarBuilder::class => CalendarBuilderFactory::class,
        SourceBuilder::class => SourceBuilderFactory::class
    ];

    private function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Return an instance of the container to access component services (eg. CalendarBuilder)
     *
     * @param array $config Component configuration (with declared calendars, sources, events, converters ...)
     * @return self
     */
    public static function create(array $config = [])
    {
        if (self::$instance === \null) {
            self::$instance = new self($config);
        }

        return self::$instance;
    }

    /**
     * Retrieve a registered instance
     *
     * @param string $name
     * @return object|array
     */
    public function get($name)
    {
        if ($name === 'config') {
            return $this->config;
        } else if (true === $this->has($name)) {
            return $this->instances[$name];
        } elseif (\array_key_exists($name, $this->factories)) {
            $factoryClassName = $this->factories[$name];
            $factory = new $factoryClassName();

            $this->instances[$name] = $factory->__invoke($this);
            return $this->instances[$name];
        }

        throw new \Exception("Service with name {$name} not found.");
    }

    /**
     * Test whether a service exists in container
     *
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return (true === isset($this->instances[$name]));
    }

    public function getConfig()
    {
        return $this->config;
    }
}
