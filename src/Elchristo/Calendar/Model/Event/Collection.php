<?php

namespace Elchristo\Calendar\Model\Event;

use ArrayIterator;
use IteratorAggregate;
use Elchristo\Calendar\Model\Event\CalendarEventInterface;
use Zend\Hydrator\Reflection as ReflectionHydrator;

/**
 * Transversable collection of calendar events
 */
class Collection implements IteratorAggregate
{
    /** @var array List of calendar events */
    private $events = [];

    /**
     * Return all events of the collection
     *
     * @return array
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Return all events of the collection as associative array
     *
     * @return array
     */
    public function toArray()
    {
        $events = [];
        $hydrator = new ReflectionHydrator();
        foreach ($this->events as $key => $event) {
            $events[$key] = $hydrator->extract($event);
        }

        return $events;
    }

    /**
     * Get array iterator to loop all events
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->events);
    }

    /**
     * Add a new event to the collection
     *
     * @param CalendarEventInterface $event
     * @return Collection
     */
    public function add(CalendarEventInterface $event)
    {
        $this->events[$event->getId()] = $event;
        return $this;
    }

    /**
     * Add multiple events (another collection) to the collection
     *
     * @param Collection $events
     * @return Collection
     */
    public function addEvents(Collection $events)
    {
        foreach ($events->getIterator() as $e) {
            $this->add($e);
        }

        return $this;
    }

    /**
     * Remove an event from the collection
     *
     * @param mixed integer|CalendarEventInterface $idOrEvent
     * @return Collection
     */
    public function remove($idOrEvent)
    {
        if (\is_int($idOrEvent)) {
            unset($this->events[$idOrEvent]);
        } elseif (\is_object($idOrEvent)) {
            unset($this->events[$idOrEvent->getId()]);
        }
        return $this;
    }

    /**
     * Return the current event of the collection
     * @return CalendarEventInterface
     */
    public function current()
    {
        return \current($this->events);
    }

    /**
     * @return CalendarEventInterface
     */
    public function next()
    {
        return \next($this->events);
    }

    /**
     * @return CalendarEventInterface
     */
    public function prev()
    {
        return \prev($this->events);
    }

    /**
     * Return single event identifier key‚
     * @return mixed
     */
    public function key()
    {
        return \key($this->events);
    }

    /**
     * Test whether there is another event in the collection after the current one
     * @return CalendarEventInterface|false
     */
    public function valid()
    {
        return (false !== \next($this->events)) ? current($this->events) : false;
    }

    /**
     * Remove all events from the collection
     * @return Collection
     */
    public function clear()
    {
        $this->events = [];
        return $this;
    }

    /**
     * Test whether a specific event exists within the collection
     *
     * @param CalendarEventInterface $event
     * @return boolean
     */
    public function contains(CalendarEventInterface $event)
    {
        return \in_array($event, $this->events);
    }

    /**
     * Find a single event within the collection by its unique identifier
     *
     * @param integer $id Unique event identifier
     * @return mixed CalendarEventInterface|null
     */
    public function get($id)
    {
        return
            (isset($this->events[$id]))
                ? $this->events[$id]
                : null;
    }

    /**
     * Replace an event by another one
     *
     * @param integer                $id    Unique event identifier
     * @param CalendarEventInterface $event New event
     * @return array
     */
    public function set($id, CalendarEventInterface $event)
    {
        $this->events[$id] = $event;
        return $this->events;
    }

    /**
     * Removes an event from the collection
     *
     * @param CalendarEventInterface $event
     * @return boolean TRUE if successfully removed, otherwise FALSE
     */
    public function removeElement(CalendarEventInterface $event)
    {
        $key = \array_search($event, $this->events, true);

        if ($key !== false) {
            unset($this->events[$key]);

            return true;
        }

        return false;
    }

    /**
     * Rewind the iterator
     *
     * @return array
     */
    public function rewind()
    {
        \reset($this->events);
        return $this->events;
    }

    /**
     * Returns the number of events in the collection
     *
     * @return integer
     */
    public function count()
    {
        return \count($this->events);
    }

    public function containsKey($key)
    {
        return \array_key_exists($key, $this->events);
    }

    public function getKeys()
    {
        return \array_keys($this->events);
    }

    public function getValues()
    {
        return \array_values($this->events);
    }

    public function isEmpty()
    {
        return empty($this->events);
    }
}
