<?php

namespace Elchristo\Calendar\Model\Event;

use Elchristo\Calendar\Model\Event\AbstractCalendarEvent;
use Elchristo\Calendar\Model\Event\ColoredEventInterface;
use Elchristo\Calendar\Model\Event\ColoredEventTrait;
use Elchristo\Calendar\Service\Color\ColorStrategyAwareTrait;

/**
 * Default calendar event
 */
class DefaultCalendarEvent extends AbstractCalendarEvent implements ColoredEventInterface
{
    use ColoredEventTrait;
    use ColorStrategyAwareTrait;
}
