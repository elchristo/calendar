<?php

namespace Elchristo\Calendar\Service;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Elchristo\Calendar\Service\SourceLocator;

/**
 * Factory to build source locator instance
 */
class SourceLocatorFactory implements FactoryInterface
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
        return new SourceLocator($container);
    }
}
