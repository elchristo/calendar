<?php

namespace Elchristo\Calendar\Test\unit\Stub;

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
            ]
        ]
    ]
];

if (is_file(__DIR__ . '/config.local.php')) {
    include(__DIR__ . '/config.local.php');
}

return $config;
