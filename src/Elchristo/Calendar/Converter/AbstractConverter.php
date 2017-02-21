<?php

namespace Elchristo\Calendar\Converter;

use Elchristo\Calendar\Converter\ConverterInterface;
use Elchristo\Calendar\Converter\ConvertibleEventFactory;

/**
 * Abstract converter
 */
abstract class AbstractConverter implements ConverterInterface
{
    /** @var ConvertibleEventFactory */
    protected $eventFactory;

    /**
     *
     * @param ConvertibleEventFactory $eventFactory
     */
    public function __construct(ConvertibleEventFactory $eventFactory)
    {
        $this->eventFactory = $eventFactory;
    }

    /**
     *
     * @return ConvertibleEventFactory
     */
    public function getConvertibleEventFactory()
    {
        return $this->eventFactory;
    }
}
