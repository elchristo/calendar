<?php

use Elchristo\Calendar\Service\SourceLocator;
use Elchristo\Calendar\Service\SourceLocatorFactory;
use Elchristo\Calendar\Service\Builder\CalendarBuilder;
use Elchristo\Calendar\Service\Builder\CalendarBuilderFactory;
use Elchristo\Calendar\Service\Builder\SourceBuilder;
use Elchristo\Calendar\Service\Builder\SourceBuilderFactory;
use Elchristo\Calendar\Service\Builder\EventBuilder;
use Elchristo\Calendar\Service\Config\Config;
use Elchristo\Calendar\Service\Color\DefaultColorStrategy;


return [
    'invokables' => [
        Config::class,
        EventBuilder::class,
        DefaultColorStrategy::class
    ],
    'factories' => [
        SourceLocator::class => SourceLocatorFactory::class,
        CalendarBuilder::class => CalendarBuilderFactory::class,
        SourceBuilder::class => SourceBuilderFactory::class,
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
