<?php

namespace Elchristo\Calendar\Model\Event;

use Elchristo\Calendar\Service\Color\ColorStrategyAwareInterface;

/**
 * Trait with methods for colored calendar events
 */
trait ColoredEventTrait
{
    /** @var string */
    protected $colorCode = '#aaaaaa';

    /**
     * Return events color code
     *
     * @return string Le code couleur
     */
    public function getColorCode()
    {
        return ($this instanceof ColorStrategyAwareInterface)
            ? $this->getColorStrategy()->getColorCodeByEvent()
            : $this->colorCode;
    }

    /**
     * Change events color code
     *
     * @param string $colorCode Color code (eg. #9f9f9f)
     */
    public function setColorCode($colorCode)
    {
        $this->colorCode = $colorCode;
        return $this;
    }
}
