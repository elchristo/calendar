<?php

namespace Elchristo\Calendar\Service\Color;

use Elchristo\Calendar\Model\Event\ColoredEventInterface;
use Elchristo\Calendar\Service\Color\ColorStrategyInterface;

/**
 * Abstract color strategy
 */
abstract class AbstractColorStrategy implements ColorStrategyInterface
{
    const DEFAULT_EVENT_COLOR = '#aaaaaa';

    /** @var array */
    protected $colorConfig = [];

    /** @var ColoredEventInterface */
    protected $event;

    /** @var array Additional attributes */
    protected $attributes = [];

    /**
     * Return the color code for a calendar event
     * either by implemented constante (COLOR_CODE_DEFAULT)
     * or by the DEFAULT_EVENT_COLOR constante of the abstract color strategy
     *
     * @return string
     */
    public function getDefaultColorCodeByEvent()
    {
        $reflClass = new \ReflectionClass($this->getEvent());
        return ($reflClass->hasConstant('COLOR_CODE_DEFAULT'))
            ? $reflClass->getConstant('COLOR_CODE_DEFAULT')
            : self::DEFAULT_EVENT_COLOR;
    }

    /**
     * Return color codes from configuration
     *
     * @return array
     */
    public function getColorConfig()
    {
        return $this->colorConfig;
    }

    /**
     * Inject color configuration
     *
     * @param array $config
     */
    public function setColorConfig(array $config)
    {
        $this->colorConfig = $config;
        return $this;
    }

    /**
     * Return calendar event
     * @return ColoredEventInterface
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Inject the calendar event to which the color strategy should be applied
     *
     * @param ColoredEventInterface $event
     * @return AbstractColorStrategy
     */
    public function setEvent(ColoredEventInterface $event)
    {
        $this->event = $event;
        return $this;
    }

    /**
     * Return additional attributes
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Change additional attributes
     *
     * @param array $attributes
     * @return AbstractColorStrategy
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }
}
