<?php

namespace Elchristo\Calendar\Model\Event;

use Elchristo\Calendar\Service\Color\ColorStrategyAwareInterface;

/**
 * Interface to be implemented by colored calendar events
 */
interface ColoredEventInterface extends ColorStrategyAwareInterface
{
    /**
     * Return events color code
     * @return string
     */
    public function getColorCode();

    /**
     * Change events color code
     *
     * @param string $colorCode
     */
    public function setColorCode(string $colorCode);
}
