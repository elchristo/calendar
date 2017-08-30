<?php

namespace Elchristo\Calendar\Converter\FullCalendar\Event;

/**
 * Default FullCalendar event implementation
 */
class DefaultFullCalendarEvent extends AbstractFullCalendarEvent
{
    public function convert()
    {
        return $this->asJson();
    }
}
