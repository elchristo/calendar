<?php

namespace Elchristo\Calendar\Model\Source;

use Elchristo\Calendar\Exception\RuntimeException;
use Elchristo\Calendar\Model\Source\SourceInterface;
use Elchristo\Calendar\Model\Event\Collection as EventsCollection;
use Elchristo\Calendar\Service\Builder\EventBuilder;
use Zend\Stdlib\ArrayUtils;

/**
 * Abstract events source
 */
abstract class AbstractSource implements SourceInterface
{
    /** @var string Unique source identifier */
    protected $identifier;

    /** @var array Source options */
    protected $options = [];

    /** @var array Fetched raw source data results */
    protected $fetchedResults = [];

    /** @var EventsCollection Fetched calendar events by the events source */
    protected $eventsCollection;

    /** @var EventBuilder Events factory instance */
    protected $eventBuilder;

    /** @var array */
    private $canonicalizeChars = [ '-' => '', '_' => '', ' ' => '', '\\' => '', '/' => '' ];

    public function __construct($identifier = null, array $options = [])
    {
        $this->initIdentifier($identifier);
        $this->options = $options;
        $this->eventsCollection = new EventsCollection();
    }

    public function getEventsCollection()
    {
        return $this->eventsCollection;
    }

    /**
     * Initialize the unique identifier of the events source
     *
     * @param mixed $identifier
     * @return string|int
     */
    private function initIdentifier($identifier)
    {
        $class = \get_called_class();

        if (\is_string($identifier) && !empty($identifier)) {
            $this->identifier = \strtolower(\strtr($identifier, $this->canonicalizeChars));
        } else if (\defined("{$class}::IDENTIFIER")) {
            $this->identifier = $class::IDENTIFIER;
        } else {
            $this->identifier = \strtolower(\strtr($class, $this->canonicalizeChars));
        }
    }

    /**
     * Return the unique source name (identifier)
     * @return string
     */
    final public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Retrieve events data, build standard event objects and group them into one events collection
     *
     * @return EventsCollection
     * @throws RuntimeException
     */
    public function getEvents($refetch = \false)
    {
        if (0 === $this->eventsCollection->count() || \true === $refetch) {
            $this->eventsCollection->clear();
            try {
                $this->fetchedResults = $this->fetchResults();
                $this->buildEvents($this->fetchedResults);
            } catch (\Exception $e) {
                throw new RuntimeException(\sprintf('Unable to build events collection for source class %s. Reason : %s', \get_called_class(), $e->getMessage()));
            }
        }

        return $this->eventsCollection;
    }

    /**
     * Return the raw source data results
     *
     * @return array
     */
    public function getFetchedResults()
    {
        return $this->fetchedResults;
    }

    /**
     * Return the source options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Merge additional options into the existing source options
     *
     * @return self
     */
    final public function addOptions(array $options)
    {
        if (\is_array($options)) {
            $this->options = ArrayUtils::merge($this->options, $options);
        }

        return $this;
    }

    /**
     * Return the criteria used in events calculation
     *
     * @return array
     */
    final public function getCriteria()
    {
        return (\array_key_exists('criteria', $this->options))
            ? $this->options['criteria']
            : [];
    }

    /**
     * Add a new criteria
     *
     * @return self
     */
    final public function addCriteria(array $criteria)
    {
        $this->options['criteria'] = ArrayUtils::merge($this->getCriteria(), $criteria);

        return $this;
    }

    /**
     * Return factory instance to build events
     *
     * @return EventBuilder
     */
    final public function getEventBuilder()
    {
        return $this->eventBuilder;
    }

    /**
     * Inject events factory into the source while creation of the source
     *
     * @param EventBuilder $builder
     * @return self
     */
    final public function setEventBuilder(EventBuilder $builder)
    {
        $this->eventBuilder = $builder;
        return $this;
    }

    /**
     * Method implemented to fetch source data which is passed to buildEvents function
     *
     * @return array
     */
    abstract protected function fetchResults();

    /**
     * Method implemented to transform fetched raw data into calendar events
     *
     * @return EventsCollection
     */
    abstract protected function buildEvents($fetchedResults);

}
