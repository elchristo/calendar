<?php

namespace Elchristo\Calendar\Service\Builder;

use Elchristo\Calendar\Service\Builder\SourceBuilder;
use Elchristo\Calendar\Service\Builder\SourceBuilderFactory;
use Elchristo\Calendar\Service\Config\Config;
use Interop\Config\ConfigurationTrait;
use Interop\Config\RequiresConfigId;
use Interop\Container\ContainerInterface;

/**
 * Factory to create and prepare builder of calendar instances
 * (either with static "create" method or by using a service container)
 */
class CalendarBuilderFactory implements RequiresConfigId
{
    use ConfigurationTrait;

    /**
     * Static factory method to build calendar builder instance
     *
     * @param array $config Calendar builder configuration (with calendars, sources, events, ...)
     * @return CalendarBuilder
     */
    public static function create(array $config = [])
    {
        if (!isset($config['elchristo']['calendar'])) {
            throw new InvalidArgumentException('Configuration array is missing at least one of the two mandatory route keys "elchristo => [ "calendar" => [ ... ] ]".');
        }

        $configProvider = new Config($config['elchristo']['calendar']);
        $calendarBuilder = new CalendarBuilder();
        $calendarBuilder
            ->setConfig($configProvider)
            ->setSourceBuilder(SourceBuilderFactory::create($configProvider));

        return $calendarBuilder;
    }

    /**
     * Factory method to use in case of service container use (implementing ContainerInterface).
     * Calendar configuration must be declared within app configuration ("config" as root key)
     *
     * @example $container->get('CalendarBuilder');
     *
     * @param ContainerInterface $container Any service container implementing ContainerInterface
     *
     * @return CalendarBuilder
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $this->options($container->get('config'), 'calendar');
        $builder = new CalendarBuilder();
        $builder
            ->setConfig(new Config($config))
            ->setSourceBuilder($container->get(SourceBuilder::class));

        return $builder;
    }

    /**
     * Return configuration options from main configuration
     *
     * @return array
     */
    public function dimensions()
    {
        return ['elchristo'];
    }
}
