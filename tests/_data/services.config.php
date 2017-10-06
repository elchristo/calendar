<?php

use Elchristo\Calendar\Service;
use Elchristo\Calendar\Service\SourceLocator;
use Elchristo\Calendar\Service\SourceLocatorFactory;
use Elchristo\Calendar\Service\Builder\AbstractEventFactory;
use Elchristo\Calendar\Test\unit\Stub;

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
        Stub\TestEventBasic::class => AbstractEventFactory::class,
    ]
];
