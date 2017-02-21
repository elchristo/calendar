<?php

namespace Elchristo\Calendar\Service\Color;

use Elchristo\Calendar\Service\Color\ColorStrategyAwareInterface;
use Elchristo\Calendar\Service\Color\ColorStrategyInterface;

/**
 * Trait with color strategy methods
 */
trait ColorStrategyAwareTrait
{
    /** @var ColorStrategyInterface Color strategy to apply */
    protected $colorStrategy;

    /**
     * Inject color strategy
     *
     * @param ColorStrategyInterface $strategy The strategy
     * @return ColorStrategyAwareInterface
     */
    public function setColorStrategy(ColorStrategyInterface $strategy)
    {
        $this->colorStrategy = $strategy;
    }

    /**
     * @return ColorStrategyInterface
     */
    public function getColorStrategy()
    {
        return $this->colorStrategy;
    }
}
