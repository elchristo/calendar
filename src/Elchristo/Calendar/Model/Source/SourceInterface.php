<?php

namespace Elchristo\Calendar\Model\Source;

use Elchristo\Calendar\Service\Builder\EventBuilder;

/**
 *
 */
interface SourceInterface
{
    public function getIdentifier();
    public function getEvents();
    public function getEventBuilder();
    public function setEventBuilder(EventBuilder $builder);
    public function getFetchedResults();
    public function getOptions();
    public function getCriteria();
}
