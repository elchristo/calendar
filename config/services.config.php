<?php

use Elchristo\Calendar\Service;
use Elchristo\Calendar\Service\SourceLocator;
use Elchristo\Calendar\Service\SourceLocatorFactory;

return [
    'invokables' => [
        Service\Config\Config::class,
        Service\Builder\EventBuilder::class => Service\Builder\EventBuilder::class,
        Service\Color\DefaultColorStrategy::class => Service\Color\DefaultColorStrategy::class,
    ],
    'factories' => [
        SourceLocator::class => SourceLocatorFactory::class,
        Service\Builder\CalendarBuilder::class => Service\Builder\CalendarBuilderFactory::class,
        Service\Builder\SourceBuilder::class => Service\Builder\SourceBuilderFactory::class,
    ],
    'abstract_factories' => [
        /*
         * You can declare your calendar events under the "abstract_factories" key to use AbstractEventFactory for building instances
         * Important notice :
         *      the listed classes either have to implement CalendarEventInterface
         *      or their name ends on "CalendarEvent"
         */
        // MySpecialCalendarEvent::class => \Elchristo\Calendar\Service\Builder\AbstractEventFactory::class,
    ]
];
