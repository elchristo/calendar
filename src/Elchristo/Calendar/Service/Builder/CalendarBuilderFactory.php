<?php

namespace Elchristo\Calendar\Service\Builder;

use Elchristo\Calendar\Service\Config\Config;
use Elchristo\Calendar\Service\Builder\SourceBuilder;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory to create and prepare builder of calendar instances
 * (either with static "create" method or by using a service container)
 */
class CalendarBuilderFactory implements FactoryInterface
{
    /**
     * Factory method to use in case of service container use (implementing ContainerInterface).
     * Calendar configuration must be declared within app configuration ("config" as root key)
     *
     * @example $container->get(CalendarBuilder::class);
     *
     * @param ContainerInterface $container     Any service container implementing ContainerInterface
     * @param string             $requestedName
     * @param null|array         $options
     *
     * @return CalendarBuilder
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = isset($options['elchristo']) && isset($options['elchristo']['calendar'])
            ? $options['elchristo']['calendar']
            : [];

        $builder = new CalendarBuilder();
        $builder
            ->setConfig(new Config($config))
            ->setSourceBuilder($container->get(SourceBuilder::class));

        return $builder;
    }
}
