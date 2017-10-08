<?php

namespace Elchristo\Calendar\Test\unit\Stub;

use Elchristo\Calendar\Test\unit\Stub\TestColorStrategy;

/*
 * Configuration for unit tests
 */

$config = [
    'elchristo' => [
        'calendar' => [
            'converters' => [
                'Ical' => [
                    TestEventIcal::class => TestEventIcalConverter::class,
                ]
            ],

            'colors' => [
                'strategies' => [
                    'MyFirstColorStrategyAlias' => TestColorStrategy::class,
                    'MySecondColorStrategyAlias' => [
                        'name' => TestColorStrategy::class
                    ]
                ]
            ]
        ]
    ]
];

if (is_file(__DIR__ . '/config.local.php')) {
    include(__DIR__ . '/config.local.php');
}

return $config;
