<?php

namespace Elchristo\Calendar\Service\Builder;

use Elchristo\Calendar\Service\SourceLocator;
use Elchristo\Calendar\Service\Config\Config;
use Elchristo\Calendar\Service\Builder\SourceBuilder;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory to create and prepare source builder instance
 * (either with static "create" method or by using a service container)
 */
class SourceBuilderFactory implements FactoryInterface
{
    /**
     * Factory method
     *
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array              $options
     *
     * @return SourceBuilder
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $locator = new SourceLocator($container);
        $builder = new SourceBuilder($locator);

        return $builder;
    }
}
