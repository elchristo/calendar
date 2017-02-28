# Calendar builder and converter

`Calendar` is a PHP library to create custom calendars composed of self defined event sources.
Furthermore you can convert built calendars easily into various output formats (eg. Json, iCalendar, ...).

# Table of Contents

 - Installation
 - Basic usage
 - Full example
 - Options

## Installation

    composer require elchristo/calendar

## Basic usage

    // calendar builder
    $calendarBuilder = CalendarBuilderFactory::create($config); // for alternative service container implementation see full example below
    $calendar = $calendarBuilder->build('CalendarName');
    $calendar->addSource('SourceName');
    $events = $calendar->getEvents(); // gives you a traversable collection of calendar events

    // convert calendar into json
    $json = Converter::convert($calendar, 'json');

    // convert calendar into "iCalendar" format (RFC 2445, VCALENDAR)
    $ics = Converter::convert($calendar, 'ical');

## Full example

### Configuration

First of all you need to create a library specific configuration.
This can either be included by using a file or your app configuration accessible by a service container.

Below you can see a basic example configuration file :

    <?php

    namespace Elchristo\Calendar;

    return [
        'elchristo' => [

            'calendar' => [

                // calendars (optional)
                'calendars' => [
                    'MyCalendar' => My\Calendar\ClassName::class
                ],

                // calendar event sources (mandatory)
                'sources' => [
                    'MySource' => My\Source\ClassName::class
                ],

                // source events (mandatory)
                'events' => [
                    'MyEvent' => My\Event\ClassName::class
                ],

                // coverter strategies
                'converters' => [
                    'Json' => [
                        My\Event\ClassName::class => My\Converter\ClassName::class
                    ]

                ],

                // event color configuration
                'colors' => [
                    'codes' => [
                        // some predefined color codes to be used by e.g. a color strategy

                    ],
                    'strategies' => [
                        'MyColorStrategy' => My\ColorStrategy\ClassName::class
                    ]
                ]

            ]
        ]
    ];

### Build an events source

To build an events source you just need to create a class implementing the `SourceInterface` or even easier extending the `AbstractSource` class with default attributes and behaviour.
Afterwards you simply have to implement the two methods `fetchResults` and `buildEvents`.

Example source class :

    <?php

    use Elchristo\Calendar\Model\Source\AbstractSource;

    class MySource extends AbstractSource
    {
        protected function fetchResults()
        {
            /*
             * Retrieve your events data from wherever you want (database, file, ...)
             * This should result in an associative array like the example below
             * Hint : array keys matching event property names makes it easy to transform results into events
             */
            $events = [
                0 => [
                    'id' => 1,
                    'start' => new \DateTime(),
                    'end' => new \DateTime,
                    'title' => 'event title 1',
                    'description' => 'a brief description',
                    'alldayEvent' => false
                ],
                . . .
            ];

            return $events;‚
        }

        protected function buildEvents($results)
        {
            // this method is called after the "fetchResults" methods and recieves its results ...

            $eventsBuilder = $this->getEventBuilder();
            $eventsCollection = $this->getEventsCollection();

            foreach ($results as $result) :
                $event = $eventsBuilder->build('MyEvent', $result, $this->getOptions());
                $eventsCollection->add($event);
            endforeach;

            return $eventsCollection;
        }
    }



### Retrieve the calendar builder

    // solution 1 : CalendarBuilderFactory
    $calendarBuilder = CalendarBuilderFactory::create($config);

    // solution 2 (service container)
    $container = ServiceContainer::create($config); // if you use the internal container
    $calendarBuilder = $container->get('CalendarBuilder');

### Build a calendar and add event sources

Then use it to build an instance of your calendar and add any of your defined calendar sources.

    $calendar = $calendarBuilder->build('MyFirstCalendar');
    // if no calendar with the name "MyFirstCalendar" has been declared in configuration
    // an empty default calendar named "MyFirstCalendar" is built

    $calendar
        ->addSource('SourceA')
        ->addSource('SourceB');

    // sourceA and sourceB have to be declared in configuration

### Retrieve calendar events

When you call the `getEvents` method on the calendar instance the component will retrieve the data results of each source and build standardized calendar events (as implemented by the source classes).
Simply iterate over the results collection and do with it whatever you want.

    $events = $calendar->getEvents();
    foreach ($events as $event) :
        echo $event->getId();
        echo $event->getTitle();
        echo $event->getDescription();
        echo $event->getStart()->format('d/m/Y H:i');
        echo $event->getEnd()->format('d/m/Y H:i');
    endforeach;

## Options

### prefix_identifier

`string`

This option is used to prefix the event id (meaningful if you have different sources which potentially retrieve events with same identifiers)

### color_strategy

`string` or `array`

If your source contains calendar events implementing the `ColoredEventInterface` you might be interested in using color strategies,.
Color strategies give you the possibility to color an event by conditions.
If you want for example apply a color to an event depending on if it is public or not, you can implement a color strategy for that.

Example use in options when building a calendar source :

    'color_strategy' => 'ColorByEventStatus'

    'color_strategy' => [
        'name' => 'ColorByEventStatus',
        'attributes' => [
            ...
        ]
    ]

Example color strategy class implementation :

    use Elchristo\Calendar\Service\Color\AbstractColorStrategy;

    class ColorByEventStatus extends AbstractColorStrategy
    {
        public function getColorCodeByEvent()
        {
            return ($this->getEvent()->isPublic())
                ? '#00ff00'
                : '#ff0000';
        }
    }
