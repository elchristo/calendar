<?php

namespace Elchristo\Calendar\Service\Color;

use Elchristo\Calendar\Service\Color\AbstractColorStrategy;

/**
 * Default color strategy (applied to colored calendar events when no color strategy was found)
 */
class DefaultColorStrategy extends AbstractColorStrategy
{
    /**
     * Return default color of the calendar event
     * @return string
     */
    public function getColorCodeByEvent()
    {
        return $this->getDefaultColorCodeByEvent();
    }
}
