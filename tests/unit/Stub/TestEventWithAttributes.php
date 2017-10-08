<?php

namespace Elchristo\Calendar\Test\unit\Stub;

use Elchristo\Calendar\Model\Event\AbstractCalendarEvent;
use Elchristo\Calendar\Model\Event\ColoredEventInterface;
use Elchristo\Calendar\Model\Event\ColoredEventTrait;
use Elchristo\Calendar\Service\Color\ColorStrategyAwareTrait;

/**
 * Calendar event to test accès on values of special attributes
 */
class TestEventWithAttributes extends AbstractCalendarEvent implements ColoredEventInterface
{
    use ColoredEventTrait;
    use ColorStrategyAwareTrait;

    protected $attributeA;
    protected $attributeB;
    protected $attributeC;

    public function setAttributeB($value)
    {
        $this->attributeB = $value;
        return $this;
    }

    public function getAttributeB()
    {
        return $this->attributeB;
    }
}
