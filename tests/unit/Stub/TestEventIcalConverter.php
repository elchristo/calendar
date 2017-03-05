<?php

namespace Elchristo\Calendar\Test\unit\Stub;

use Elchristo\Calendar\Converter\Ical\Event\AbstractIcalEvent;

/**
 * Converter to test calendar event conversation into iCal format
 */
class TestEventIcalConverter extends AbstractIcalEvent
{
    /**
     * Convert calendar event into iCal format
     * @return type
     */
    public function convert()
    {
        $this->setSummary($this->event->getSpecialAttribute())
            ->setStatus('TENTATIVE');

        return $this->asIcal();
    }

}
