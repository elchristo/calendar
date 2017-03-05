<?php

namespace Elchristo\Calendar\Test\unit\Stub;

use Elchristo\Calendar\Model\Source\AbstractSource;

/**
 * Calendar source stub filled with random "Faker" data
 */
class TestFakerSource extends AbstractSource
{
    protected function fetchResults()
    {
        $faker = \Faker\Factory::create();

        $events = [];
        for ($i = 0; $i <= 50; $i++) {
            $dateFrom = $faker->dateTimeThisYear;
            $dateFrom->setTimezone(new \DateTimeZone('Europe/Berlin'));
            $dateTo = clone($dateFrom);
            $events[] = [
                'id' => $faker->randomNumber(5),
                'title' => $faker->sentence(3),
                'titleShort' => $faker->sentence(2),
                'description' => $faker->text(40),
                'start' => $dateFrom,
                'end' => $dateTo->add(new \DateInterval('PT' . $faker->randomNumber(3) . 'M')),
            ];
        }

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
        $options['prefix_identifier'] = 'idprefix_';

        foreach ($results as $result) :
            $event = $eventsBuilder->build('MyEvent', $result, $options);
            $eventsCollection->add($event);
        endforeach;

        return $eventsCollection;
    }
}
