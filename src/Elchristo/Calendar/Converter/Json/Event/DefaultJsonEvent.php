<?php

namespace Elchristo\Calendar\Converter\Json\Event;

/**
 * Default Json event implementation
 */
class DefaultJsonEvent extends AbstractJsonEvent
{
    public function convert()
    {
        return $this->asJson();
    }
}
