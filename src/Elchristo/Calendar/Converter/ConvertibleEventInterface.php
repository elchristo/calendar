<?php

namespace Elchristo\Calendar\Converter;

/**
 * Interface to define convertible calendar events
 */
interface ConvertibleEventInterface
{
    /**
     * Method to convert a single calendar event into converter format
     */
    public function convert();

    /**
     * @return array
     */
    public function getOptions();

    /**
     * @return array
     */
    public function setOptions(array $options);
}
