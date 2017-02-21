<?php

namespace Elchristo\Calendar\Service\Builder;

use Elchristo\Calendar\Service\SourceLocator;
use Elchristo\Calendar\Service\Config\Config;
use Elchristo\Calendar\Service\Builder\SourceBuilder;
use Interop\Config\ConfigurationTrait;
use Interop\Config\RequiresConfigId;
use Interop\Container\ContainerInterface;

/**
 * Factory to create and prepare source builder instance
 * (either with static "create" method or by using a service container)
 */
class SourceBuilderFactory implements RequiresConfigId
{
    use ConfigurationTrait;

    /**
     * Static factory method to build calendar builder instance
     *
     * @param Config $config config provider onstance
     * @return CalendarBuilder
     */
    public static function create(Config $config)
    {
        $locator = new SourceLocator();
        $locator->setConfig($config);

        $builder = new SourceBuilder();
        $builder->setSourceLocator($locator);

        return $builder;
    }

    /**
     * Factory method
     *
     * @param ContainerInterface $container
     *
     * @return SourceBuilder
     */
    public function __invoke(ContainerInterface $container)
    {
        $builder = new SourceBuilder();
        $options = $this->options($container->get('config'), 'calendar');
        $config = new Config($options);
        $locator = new SourceLocator();
        $locator->setConfig($config);

        $builder->setSourceLocator($locator);

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
