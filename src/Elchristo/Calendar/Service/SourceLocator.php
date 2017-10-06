<?php

namespace Elchristo\Calendar\Service;

use Elchristo\Calendar\Exception\RuntimeException;
use Elchristo\Calendar\Model\Source\SourceInterface;
use Interop\Container\ContainerInterface;

/**
 * Class to locate calendar event sources (service container facade)
 */
class SourceLocator
{
    /** @var ContainerInterface */
    protected $container = null;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     *
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return $this->container->has($name);
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
        if (!$this->isValidSource($name)) {
            throw new RuntimeException("Calendar source with name {$name} has not been declared in configuration.");
        }

        return $this->container->get($name);
    }

    /**
     *
     * @param string $name
     * @param array  $options
     * @return SourceInterface
     */
    public function build($name, array $options = [])
    {
        return $this->container->build($name, $options);
    }

    /**
     * Test whether a source has been declared in configuration
     *
     * @param string $name The (class)name of the source to verify
     * @return boolean
     */
    protected function isValidSource($name)
    {
        return ($this->container->has($name) && \is_subclass_of($this->container->get($name), SourceInterface::class))
            || (\class_exists($name) && \is_subclass_of($name, SourceInterface::class));
    }
}
