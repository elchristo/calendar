<?php

namespace Elchristo\Calendar\Service\Builder;

use Elchristo\Calendar\Service\Builder\EventBuilder;
use Elchristo\Calendar\Model\Event\CalendarEventInterface;
use Elchristo\Calendar\Service\SourceLocator;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

/**
 *
 */
class AbstractEventFactory implements AbstractFactoryInterface
{

    public function canCreate(ContainerInterface $container, $requestedName): bool
    {
        // accepting (1) class names ending on 'CalendarEvent' or (2) classes implementing CalendarEventInterface
        $nameIdentifier = 'CalendarEvent';
        return \strpos($requestedName, $nameIdentifier) === (\strlen($requestedName) - \strlen($nameIdentifier))
            || \is_subclass_of($requestedName, CalendarEventInterface::class);
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): CalendarEventInterface
    {
        if (null === $options) {
            $options = [];
        }

        $sourceLocator = $container->get(SourceLocator::class);
        $eventBuilder = EventBuilder::getInstance($sourceLocator, $options);

        $attributes = [];
        if (isset($options['attributes']) && \is_array($options['attributes'])) {
            $attributes = $options['attributes'];
            unset($options['attributes']);
        }

        return $eventBuilder->build($requestedName, $attributes, $options);
    }

}
