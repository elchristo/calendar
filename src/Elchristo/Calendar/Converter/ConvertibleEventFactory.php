<?php

namespace Elchristo\Calendar\Converter;

use Elchristo\Calendar\Exception\RuntimeException;
use Elchristo\Calendar\Model\Event\CalendarEventInterface;

/**
 * Factory to build events which can be converted into various output formats
 */
class ConvertibleEventFactory
{
    private $registeredConverters = [];

    /**
     * @param array $registeredConverters Declared converters in configuration
     */
    public function __construct(array $registeredConverters = [])
    {
        $this->registeredConverters = $registeredConverters;
    }

    /**
     * Build instance of event converter ("default" if not found)
     *
     * @param CalendarEventInterface $event  Calendar event to convert
     * @param string                 $format Output format
     * @return ConvertibleEventInterface
     */
    public function build(CalendarEventInterface $event, string $format)
    {
        $key = \get_class($event);
        $name = \ucfirst($format);

        try {
            $eventClass = (isset($this->registeredConverters[$name]) && isset($this->registeredConverters[$name][$key]))
                ? $this->registeredConverters[$name][$key]
                : __NAMESPACE__ . "\\" . $name . "\Event\Default{$name}Event";

            return new $eventClass($event);
        } catch (\Exception $e) {
            throw new RuntimeException("Impossible to build converter for calendar event of type {$event->getType()}.", $e->getCode(), $e);
        }
    }
}
