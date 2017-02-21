<?php

namespace Elchristo\Calendar\Service\Color;

use Elchristo\Calendar\Service\Color\ColorStrategyInterface;

/**
 * Interface to build calendar events colored by color strategies
 */
interface ColorStrategyAwareInterface
{
    public function setColorStrategy(ColorStrategyInterface $strategy);
    public function getColorStrategy();
}
