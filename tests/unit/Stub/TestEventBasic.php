<?php

namespace Elchristo\Calendar\Test\unit\Stub;

use Elchristo\Calendar\Model\Event\AbstractCalendarEvent;
use Elchristo\Calendar\Model\Event\ColoredEventInterface;
use Elchristo\Calendar\Model\Event\ColoredEventTrait;
use Elchristo\Calendar\Service\Color\ColorStrategyAwareTrait;

/**
 * Basic (default) calendar event used in tests
 * This event can be colored but has no specific color strategy declared
 */
class TestEventBasic extends AbstractCalendarEvent implements ColoredEventInterface
{
    use ColoredEventTrait;
    use ColorStrategyAwareTrait;
}
