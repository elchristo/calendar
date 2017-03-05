<?php

namespace Elchristo\Calendar\Test\unit\Stub;

use Elchristo\Calendar\Model\Event\AbstractCalendarEvent;
use Elchristo\Calendar\Model\Event\ColoredEventInterface;
use Elchristo\Calendar\Model\Event\ColoredEventTrait;

/**
 * Calendar event with additional attributes to test conversation into iCal format
 */
class TestEventIcal extends AbstractCalendarEvent implements ColoredEventInterface
{
    use ColoredEventTrait;

    protected $specialAttribute = null;
}
