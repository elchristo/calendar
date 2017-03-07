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

    /**
     * Private constructor (only called by "create" method)
     * @param array $config
     */
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
        $canonicalizeName = $this->canonicalizeName($name);

        if ($canonicalizeName === 'config') {
            return $this->config;
        } else if (isset($this->instances[$canonicalizeName])) {
            return $this->instances[$canonicalizeName];
        } elseif (true === $this->has($name)) {
            $factoryClassName = $this->factories[$name];
            $factory = new $factoryClassName();

            $this->instances[$canonicalizeName] = $factory->__invoke($this);
            return $this->instances[$canonicalizeName];
        }

        throw new \Exception("Service with name {$name} ({$canonicalizeName}) not found.");
    }

    /**
     * Test whether a service exists in container
     *
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return (true === isset($this->factories[$name]));
    }

    public function getConfig()
    {
        return $this->config;
    }

    private function canonicalizeName($name)
    {
        return \strtolower(\strtr($name, [ '-' => '', '_' => '', ' ' => '', '\\' => '', '/' => '' ]));
    }
}
