<?php

namespace Elchristo\Calendar\Service\Config;

/**
 * Trait with methods to retrieve and inject calendar configuration
 */
trait ConfigProviderTrait
{
    protected $config;

    /**
     * Return the calendar configuration provider
     *
     * @return Config
     */
    public function getConfig()
    {
        if (!$this->config instanceof Config) {
            $this->config = new Config();
        }

        return $this->config;
    }

    /**
     * Inject calendar configuration
     *
     * @param Config $config config provider instance
     * @return self
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
        return $this;
    }
}
