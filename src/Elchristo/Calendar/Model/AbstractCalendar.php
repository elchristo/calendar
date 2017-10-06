<?php

namespace Elchristo\Calendar\Model;

use Elchristo\Calendar\Exception\RuntimeException;
use Elchristo\Calendar\Model\AbstractSource;
use Elchristo\Calendar\Service\Builder\SourceBuilder;
use Elchristo\Calendar\Model\Event\Collection as EventsCollection;
use Elchristo\Calendar\Model\CalendarInterface;
use Elchristo\Calendar\Service\Config\ConfigAwareInterface;
use Elchristo\Calendar\Service\Config\ConfigProviderTrait;

/**
 * Default abstract calendar class
 */
abstract class AbstractCalendar implements CalendarInterface, ConfigAwareInterface
{
    use ConfigProviderTrait;

    /** @var string */
    protected $name;

    /** @var EventsCollection */
    protected $eventsCollection;

    /** @var array Source list  */
    protected $sourceList = [];

    /** @var SourceBuilder */
    protected $sourceBuilder;

    /**
     * Calendar constructor
     *
     * @param SourceBuilder    $builder
     * @param EventsCollection $eventsCollection
     */
    public function __construct(SourceBuilder $builder, EventsCollection $eventsCollection)
    {
        $this->sourceBuilder = $builder;
        $this->eventsCollection = $eventsCollection;
    }

    /**
     * Returns the name of the calendar
     * @return string
     */
    final public function getName()
    {
        return $this->name;
    }

    /**
     * Change the name of the calendar
     *
     * @param string $name New name for the calendar
     * @return self
     */
    final public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Returns the source list of the calendar
     *
     * @return array
     */
    final public function getSourceList()
    {
        return $this->sourceList;
    }

    /**
     * Returns a source of the calendar if existing
     *
     * @param string $sourceName Requested source name
     * @return AbstractSource
     * @throws RuntimeException
     */
    final public function getSource(string $sourceName)
    {
        if (!isset($this->sourceList[$sourceName])) {
            throw new RuntimeException(\sprintf("Calendar %s has no source of name %s", \get_called_class(), $sourceName));
        }

        return $this->sourceList[$sourceName];
    }

    /**
     * Returns the source builder
     *
     * @return SourceBuilder
     */
    final protected function getSourceBuilder()
    {
        return $this->sourceBuilder;
    }

    /**
     * Rebuild the events collection with the events of all sources
     *
     * @return EventsCollection
     */
    final public function getEvents()
    {
        $this->eventsCollection->clear();
        foreach ($this->getSourceList() as $source) {
            $this->eventsCollection->addEvents($source->getEvents());
        }

        return $this->eventsCollection;
    }

    /**
     * Test whether a source exists in calendar
     *
     * @param string $name The name of the requested source
     * @return boolean
     */
    final public function hasSource(string $name): bool
    {
        return isset($this->sourceList[$name]);
    }

    /**
     * Add a new source to the calendar
     *
     * @param string $name    The name of the source to add
     * @param array  $options Source options
     * @return self
     */
    final public function addSource(string $name, array $options = [])
    {
        $source = $this->getSourceBuilder()->build($name, $options);
        if (\is_object($source)) {
            $this->sourceList[$name] = $source;
        }

        return $this;
    }

    /**
     * Initialize the calendar (final step of construction process)
     * @return self
     */
    public function init()
    {
        return $this;
    }
}
