<?php

namespace Elchristo\Calendar\Converter\Json;

use Elchristo\Calendar\Converter\AbstractConverter;
use Elchristo\Calendar\Model\CalendarInterface;
use Elchristo\Calendar\Converter\ConvertibleEventInterface;

/**
 * Json converter
 */
class Json extends AbstractConverter
{
    /* @var $event ConvertibleEventInterface */

    public function convert(CalendarInterface $calendar, array $options = [])
    {
        $events = ['events' => []];
        $eventsCollection = $calendar->getEvents();

        $eventBuilder = $this->getConvertibleEventFactory();
        foreach ($eventsCollection->getIterator() as $event) {
            $jsonEvent = $eventBuilder->build($event, 'Json');
            $jsonEvent->setOptions($options);
            $events['events'][] = $jsonEvent->asArray();
        }

        return \str_replace("\/", '/', \json_encode($events));
    }
}
