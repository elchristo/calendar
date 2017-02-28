<?php

namespace Elchristo\Calendar\Converter\Ical;

use Elchristo\Calendar\Converter\AbstractConverter;
use Elchristo\Calendar\Model\CalendarInterface;

/**
 * Ical converter (VCALENDAR - RFC 2445)
 *
 * @see https://www.ietf.org/rfc/rfc2445.txt
 * @see https://wikipedia.org/wiki/ICalendar
 */
class Ical extends AbstractConverter
{
    const CONTENT_PREFIX = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//CALENDAR\r\n";
    const CONTENT_SUFFIX = "END:VCALENDAR\r\n";

    /**
     * Convert calendar events into iCalendar (RFC 2445)
     * @param CalendarInterface $calendar
     * @param array             $options
     * @return string
     */
    public function convert(CalendarInterface $calendar, array $options = [])
    {
        $vEvents = '';
        $eventsCollection = $calendar->getEvents();

        $eventBuilder = $this->getConvertibleEventFactory();
        foreach ($eventsCollection->getIterator() as $event) {
            $event = $eventBuilder->build($event, 'Ical');
            $event->setOptions($options);
            $vEvents .= $event->convert();
        }

        return self::CONTENT_PREFIX . \utf8_decode($vEvents) . self::CONTENT_SUFFIX;
    }

}
