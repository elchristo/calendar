<?php

namespace Elchristo\Calendar\Converter\Ical\Event;

/**
 * Default iCalendar event implementation
 */
class DefaultIcalEvent extends AbstractIcalEvent
{
    public function convert()
    {
        return $this->asIcal();
    }
}
