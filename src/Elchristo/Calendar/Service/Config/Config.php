<?php

namespace Elchristo\Calendar\Service\Config;

/**
 * Service who provides access to calendar configuration
 */
class Config
{
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
    public function __construct(array $config = [])
    {
        $this->config = $config;
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
