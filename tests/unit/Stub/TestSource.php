<?php

namespace Elchristo\Calendar\Test\unit\Stub;

use Elchristo\Calendar\Model\Source\AbstractSource;

/**
 * calendar source stub
 */
class TestSource extends AbstractSource
{
    protected function fetchResults()
    {
        $events = [];

        $events[] = [
            'id' => 123,
            'title' => "long event title",
            'titleShort' => "short title",
            'description' => "this is the first event added to the calendar events source",
            'start' => \DateTime::createFromFormat('d/m/Y', '03/11/2017'),
            'end' => \DateTime::createFromFormat('d/m/Y', '05/11/2017'),
            'alldayEvent' => true
        ];

        $events[] = [
            'id' => 456,
            'title' => "another long event title",
            'titleShort' => "another short title",
            'description' => "this is the second event added to the calendar events source",
            'start' => \DateTime::createFromFormat('d/m/Y H:i', '16/03/2017 14:00'),
            'end' => \DateTime::createFromFormat('d/m/Y H:i', '16/03/2017 16:30'),
        ];

        return $events;
    }

    protected function buildEvents($results)
    {
        if (!\is_array($results)) {
            throw new Exception\InvalidArgumentException(\sprintf('Retrieved events data must be of type array, %s given', gettype($results)));
        }

        $eventsBuilder = $this->getEventBuilder();
        $eventsCollection = $this->getEventsCollection();
        $options = $this->getOptions();

        foreach ($results as $result) :
            $event = $eventsBuilder->build('TestCalendarEvent', $result, $options);
            $eventsCollection->add($event);
        endforeach;

        return $eventsCollection;
    }
}
