<?php

namespace Elchristo\Calendar\Service\Config;

use Elchristo\Calendar\Exception\RuntimeException;

/**
 * Service who provides access to calendar configuration
 */
class Config
{
    /** @var string */
    const CONFIG_KEY_CALENDARS = 'calendars';

    /** @var string */
    const CONFIG_KEY_SOURCES = 'sources';

    /** @var string */
    const CONFIG_KEY_EVENTS = 'events';

    /** @var string */
    const CONFIG_KEY_CONVERTERS = 'converters';

    /** @var string */
    const CONFIG_KEY_COLORS = 'colors';

    /** @var array */
    private $config;

    /**
     * Constructor
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Return list of declared calendars in configuration
     * @return array
     */
    public function getRegisteredCalendars()
    {
        if (!\array_key_exists(self::CONFIG_KEY_CALENDARS, $this->config)
            || !\is_array($this->config[self::CONFIG_KEY_CALENDARS])
        ) {
            return [];
        }

        return $this->config[self::CONFIG_KEY_CALENDARS];
    }

    /**
     * Return list of declared sources in configuration
     * @return array
     */
    public function getRegisteredSources()
    {
        if (!\array_key_exists(self::CONFIG_KEY_SOURCES, $this->config)) {
            throw new RuntimeException(\sprintf('Missing configuration key "%s".', self::CONFIG_KEY_SOURCES));
        }

        return (array) $this->config[self::CONFIG_KEY_SOURCES];
    }

    /**
     * Return list of declared events in configuration
     * @return array
     */
    public function getRegisteredEvents()
    {
        if (!\array_key_exists(self::CONFIG_KEY_EVENTS, $this->config)) {
            throw new RuntimeException(\sprintf('Missing configuration key "%s".', self::CONFIG_KEY_EVENTS));
        }

        return (array) $this->config[self::CONFIG_KEY_EVENTS];
    }

    /**
     * Return list of declared converters in configuration
     * @return array
     */
    public function getRegisteredConverters()
    {
        if (!\array_key_exists(self::CONFIG_KEY_CONVERTERS, $this->config)
            || !\is_array($this->config[self::CONFIG_KEY_CONVERTERS])
        ) {
            return [];
        }

        return $this->config[self::CONFIG_KEY_CONVERTERS];
    }

    /**
     * Return list of declared color codes in configuration
     * @return array
     */
    public function getRegisteredColors()
    {
        return (isset($this->config[self::CONFIG_KEY_COLORS])
            && \is_array($this->config[self::CONFIG_KEY_COLORS])
            && isset($this->config[self::CONFIG_KEY_COLORS]['codes'])
        )
            ? $this->config[self::CONFIG_KEY_COLORS]['codes']
            : [];
    }

    /**
     * Return configured color strategies
     * @return array
     */
    public function getRegisteredColorStrategies()
    {
        return (isset($this->config[self::CONFIG_KEY_COLORS])
            && \is_array($this->config[self::CONFIG_KEY_COLORS])
            && isset($this->config[self::CONFIG_KEY_COLORS]['strategies'])
        )
            ? $this->config[self::CONFIG_KEY_COLORS]['strategies']
            : [];
    }
}
