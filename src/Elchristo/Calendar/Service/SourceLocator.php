<?php

namespace Elchristo\Calendar\Service;

use Elchristo\Calendar\Exception\RuntimeException;
use Elchristo\Calendar\Model\Source\SourceInterface;
use Elchristo\Calendar\Service\Config\ConfigAwareInterface;
use Elchristo\Calendar\Service\Config\ConfigProviderTrait;

/**
 * Class to locate configured event sources
 */
class SourceLocator implements ConfigAwareInterface
{
    use ConfigProviderTrait;

    /**
     * Find the name of the class source (declared in configuration)
     *
     * @param string $name The name of the configured source
     * @return mixed string|false
     */
    public function getSourceClassName($name)
    {
        if ($this->isRegisteredSource($name)) {
            $registeredSources = $this->getConfig()->getRegisteredSources();
            $className = $registeredSources[$name];
        }

        return !empty($className) ? $className : false;
    }

    /**
     * Find a source by its name in configuration
     *
     * @param string $name The source name
     * @return SourceInterface
     * @throws RuntimeException
     */
    public function get($name)
    {
        if (!$this->isRegisteredSource($name)) {
            throw new RuntimeException("Calendar source with name {$name} has not been declared in configuration.");
        }

        return $this->getServiceLocator()->get($name);
    }

    /**
     * Test whether a source has been declared in configuration
     *
     * @param string $name The name of the source to look for
     * @return boolean
     */
    protected function isRegisteredSource($name)
    {
        $registeredSources = $this->getConfig()->getRegisteredSources();
        return \array_key_exists($name, $registeredSources);
    }
}
