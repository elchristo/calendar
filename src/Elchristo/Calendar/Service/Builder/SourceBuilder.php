<?php

namespace Elchristo\Calendar\Service\Builder;

use Elchristo\Calendar\Exception\RuntimeException;
use Elchristo\Calendar\Exception\InvalidArgumentException;
use Elchristo\Calendar\Model\Source\SourceInterface;
use Elchristo\Calendar\Service\SourceLocator;
use Elchristo\Calendar\Service\Config\ConfigAwareInterface;
use Elchristo\Calendar\Service\Config\ConfigProviderTrait;

/**
 * Class to build calendar event sources
 */
class SourceBuilder implements ConfigAwareInterface
{
    use ConfigProviderTrait;

    /** @var SourceLocator */
    protected $sourceLocator;

    /**
     * Factory method to build and initialize an new calendar events source
     *
     * @param string $sourceName The name of the configured source to build
     * @param array  $options    Additional source options
     *
     * @return mixed SourceInterface|null
     * @throws RuntimeException
     */
    public function build($sourceName, array $options)
    {
        if (empty($sourceName)) {
            throw new InvalidArgumentException("Cannot build calendar source with invalid empty source name.");
        }

        $fullClassName = $this->getSourceLocator()->getSourceClassName($sourceName);
        if (false === $fullClassName || !\class_exists($fullClassName)) {
            throw new RuntimeException(\sprintf("Calendar source width name '%s' (class name %s) was not declared in configuration or does not exist.", $sourceName, $fullClassName));
        }

        // Create source instance
        $source = new $fullClassName($sourceName);

        if (true !== $source instanceof SourceInterface) {
            throw new RuntimeException(\sprintf("Declared calendar source '%s' needs to implement %s.", $sourceName, SourceInterface::class));
        }

        $eventBuilder = EventBuilder::getInstance($this->getSourceLocator()->getConfig());
        $source->setOptions($options)
               ->setEventBuilder($eventBuilder);

        return $source;
    }

    public function getSourceLocator()
    {
        return $this->sourceLocator;
    }

    public function setSourceLocator($sourceLocator)
    {
        $this->sourceLocator = $sourceLocator;
        return $this;
    }
}
