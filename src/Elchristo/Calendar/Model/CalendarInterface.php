<?php

namespace Elchristo\Calendar\Model;

use Elchristo\Calendar\Model\Event\Collection as EventsCollection;

/**
 * Interface to define calendars
 */
interface CalendarInterface
{
    /**
     * Return the calendar name
     *
     * @return string
     */
    public function getName();

    /**
     * Change the calendar name
     *
     * @param string $name
     * @return CalendarInterface
     */
    public function setName(string $name);

    /**
     * Calculate and return all events of all event sources attached to the calendar
     *
     * @return EventsCollection
     */
    public function getEvents();

    /**
     * Return all event sources attached to the calendar
     *
     * @return array
     */
    public function getSourceList();

    /**
     * Add a new calendar source
     *
     * @param string $name    The name of the source to be added
     * @param array  $options Additional source options
     *
     * @return CalendarInterface
     */
    public function addSource(string $name, array $options);
}
