<?php

namespace Elchristo\Calendar\Model\Event;

/**
 * Interface to be implemented by colored calendar events
 */
interface ColoredEventInterface
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
