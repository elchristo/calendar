<?php

namespace Elchristo\Calendar\Test\unit\Stub;

/*
 * Configuration for unit tests
 */

$config = [
    'elchristo' => [
        'calendar' => [
            'calendars' => [
                'TestCalendar' => TestCalendar::class
            ],
            'sources' => [
                'TestSource' => TestSource::class,
                'TestFakerSource' => TestFakerSource::class
            ],
            'events' => [
                'TestEventBasic' => TestEventBasic::class,
                'TestEventWithAttributes' => TestEventWithAttributes::class,
                'TestCalendarEventToBeConverted' => TestEventIcal::class,
            ],
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
