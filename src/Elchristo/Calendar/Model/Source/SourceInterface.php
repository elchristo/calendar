<?php

namespace Elchristo\Calendar\Model\Source;

/**
 *
 */
interface SourceInterface
{
    public function getIdentifier();
    public function getEvents();
    public function getEventBuilder();
    public function setEventBuilder($builder);
    public function getFetchedResults();
    public function getOptions();
    public function getCriteria();
}
