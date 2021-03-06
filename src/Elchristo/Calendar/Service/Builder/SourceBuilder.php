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

    public function __construct(SourceLocator $locator)
    {
        $this->sourceLocator = $locator;
    }

    /**
     * Factory method to build and initialize a calendar events source
     *
     * @param string $sourceClassName The name of the configured source to build or its classname
     * @param array  $options    Additional source options
     *
     * @return mixed SourceInterface|null
     * @throws RuntimeException
     */
    public function build($sourceClassName, array $options = [])
    {
        if (!\class_exists($sourceClassName)) {
            throw new InvalidArgumentException("Calendar source class {$sourceClassName} does not exist.");
        }

        if (!\in_array(SourceInterface::class, \class_implements($sourceClassName))) {
            throw new InvalidArgumentException(\sprintf("Declared calendar source '%s' needs to implement %s.", $sourceClassName, SourceInterface::class));
        }

        // Create source instance
        $source = new $sourceClassName($sourceClassName, $options);

        $eventBuilder = EventBuilder::getInstance($this->sourceLocator, $options);
        $source->setEventBuilder($eventBuilder);

        return $source;
    }

    public function getSourceLocator()
    {
        return $this->sourceLocator;
    }
}
