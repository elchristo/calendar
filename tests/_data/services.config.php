<?php

use Elchristo\Calendar\Service\SourceLocator;
use Elchristo\Calendar\Service\SourceLocatorFactory;
use Elchristo\Calendar\Service\Builder\CalendarBuilder;
use Elchristo\Calendar\Service\Builder\CalendarBuilderFactory;
use Elchristo\Calendar\Service\Builder\SourceBuilder;
use Elchristo\Calendar\Service\Builder\SourceBuilderFactory;
use Elchristo\Calendar\Service\Builder\AbstractEventFactory;
use Elchristo\Calendar\Service\Builder\EventBuilder;
use Elchristo\Calendar\Service\Config\Config;
use Elchristo\Calendar\Service\Color\DefaultColorStrategy;
use Elchristo\Calendar\Test\unit\Stub;

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
        Stub\TestEventBasic::class => AbstractEventFactory::class,
        Stub\TestEventWithAttributes::class => AbstractEventFactory::class,
    ]
];
