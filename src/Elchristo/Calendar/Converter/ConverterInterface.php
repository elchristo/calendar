<?php

namespace Elchristo\Calendar\Converter;

use Elchristo\Calendar\Model\CalendarInterface;

/**
 * Interface to define calendar converters
 */
interface ConverterInterface
{
    /**
     * Method to convert events of given calendar instance
     *
     * @param CalendarInterface $calendar Calendar containing events to convert
     * @param array             $options  Additional options
     */
    public function convert(CalendarInterface $calendar, array $options = []);
}
