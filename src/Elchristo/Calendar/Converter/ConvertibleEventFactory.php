<?php

namespace Elchristo\Calendar\Converter;

use Elchristo\Calendar\Exception\RuntimeException;
use Elchristo\Calendar\Model\Event\CalendarEventInterface;

/**
 * Factory to build convertible events
 */
class ConvertibleEventFactory
{
    const DEFAULT_NAMESPACE_PREFIX = __NAMESPACE__ . '\\';

    private $registeredConverters = [];

    /**
     * @param array $registeredConverters Declared converters in configuration
     */
    public function __construct(array $registeredConverters = [])
    {
        $this->registeredConverters = $registeredConverters;
    }

    /**
     * Build instance of convertable event (default event if not found)
     *
     * @param CalendarEventInterface $event  Calendar event to convert
     * @param string                 $format Output format
     */
    public function build(CalendarEventInterface $event, $format)
    {
        $key = \get_class($event);
        $name = \ucfirst($format);

        try {
            if (isset($this->registeredConverters[$name]) && \array_key_exists($key, $this->registeredConverters[$name])) {
                $eventClass = $this->registeredConverters[$name][$key];
                return new $eventClass($event);
            }

            $defaultEventClass = self::DEFAULT_NAMESPACE_PREFIX . "{$name}\Event\Default{$name}Event";
            return new $defaultEventClass($event);
        } catch (\Exception $e) {
            throw new RuntimeException("Impossible to build event of type {$event->getType()}.", $e->getCode(), $e);
        }
    }
}
