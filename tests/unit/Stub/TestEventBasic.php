<?php

namespace Elchristo\Calendar\Test\unit\Stub;

use Elchristo\Calendar\Model\Event\AbstractCalendarEvent;
use Elchristo\Calendar\Model\Event\ColoredEventInterface;
use Elchristo\Calendar\Model\Event\ColoredEventTrait;

/**
 * Basic (default) calendar event used in tests
 */
class TestEventBasic extends AbstractCalendarEvent implements ColoredEventInterface
{
    use ColoredEventTrait;
}
