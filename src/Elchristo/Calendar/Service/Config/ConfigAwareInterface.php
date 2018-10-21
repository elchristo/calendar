<?php

namespace Elchristo\Calendar\Service\Config;

use Elchristo\Calendar\Service\Config\Config;

/**
 * Interface to be implemented by classes which access calendar configuration.
 * Can be used in combination with ConfigProviderTrait.
 */
interface ConfigAwareInterface
{
    /** @return Config Calendar configuration */
    public function getConfig();

    /** @param Config $config */
    public function setConfig(Config $config);
}
