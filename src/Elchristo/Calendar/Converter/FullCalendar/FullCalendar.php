<?php

namespace Elchristo\Calendar\Converter\FullCalendar;

use Elchristo\Calendar\Converter\AbstractConverter;
use Elchristo\Calendar\Model\CalendarInterface;

/**
 * Converter for FullCalendar events
 *
 * @see https://fullcalendar.io
 */
class FullCalendar extends AbstractConverter
{
    /**
     * Convert calendar events into FullCalendar specific JSON format
     *
     * @param CalendarInterface $calendar
     * @param array             $options
     * @return string
     */
    public function convert(CalendarInterface $calendar, array $options = [])
    {
        $aEvents = [];
        $events = $calendar->getEvents();

        // Build FullCalendar events ...
        $eventBuilder = $this->getConvertibleEventFactory();
        foreach ($events->getIterator() as $event) {
            $fcEvent = $eventBuilder->build($event, 'FullCalendar');
            $fcEvent->setOptions($options);
            $aEvents[] = $fcEvent->asArray();
        }

        return \str_replace("\/", '/', \json_encode($aEvents));
    }
}
