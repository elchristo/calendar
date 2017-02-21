<?php

namespace Elchristo\Calendar\Model\Source;

/**
 *
 */
interface SourceInterface
{
    public function getEvents();
    public function getEventBuilder();
    public function setEventBuilder($builder);
}
