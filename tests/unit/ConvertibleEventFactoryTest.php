<?php

namespace Elchristo\Calendar\Test;

use Elchristo\Calendar\Service\Builder\EventBuilder;
use Elchristo\Calendar\Converter\ConvertibleEventInterface;
use Elchristo\Calendar\Converter\ConvertibleEventFactory;
use Elchristo\Calendar\Converter\Ical\Event\AbstractIcalEvent;
use Elchristo\Calendar\Service\Config\Config;

class ConvertibleEventFactoryTest extends TestCase
{
    /* @var $eventConverter ConvertibleEventInterface */

    private function getConfigProvider()
    {
        return new Config(self::getConfig()['elchristo']['calendar']);
    }

    /**
     *
     * @return ConvertibleEventFactory
     */
    private function getFactory()
    {
        $configConverters = $this->getConfigProvider()->getRegisteredConverters();
        return new ConvertibleEventFactory($configConverters);
    }

    /**
     * Test to build a convertible event and convert it to JSON
     */
    public function testCanCreateEventAndConvertItToJson()
    {
        $configProvider = $this->getConfigProvider();
        $builder = EventBuilder::getInstance($configProvider);
        $event = $builder->build('SomeUndefinedCalendarEvent');
        $factory = $this->getFactory();

        // json conversion
        $jsonConverter = $factory->build($event, 'json');
        $this->assertInstanceOf(ConvertibleEventInterface::class, $jsonConverter);
        $this->assertJson($jsonConverter->convert());
    }

    /**
     * Test to build a convertible event and convert it to iCal
     */
    public function testCanCreateEventAndConvertItToIcal()
    {
        $summary = 'summary in special event attribut';
        $statusInEventConverter = 'TENTATIVE';

        $configProvider = $this->getConfigProvider();
        $builder = EventBuilder::getInstance($configProvider);
        $event = $builder->build('TestCalendarEventToBeConverted');
        $event->setSpecialAttribute($summary);

        // iCal event conversion
        $factory = $this->getFactory();
        $iCalEventConverter = $factory->build($event, 'ical');
        $this->assertInstanceOf(ConvertibleEventInterface::class, $iCalEventConverter);
        $iCalEvent = $iCalEventConverter->convert();

        $this->assertStringStartsWith('BEGIN:VEVENT', $iCalEvent);
        $this->assertStringEndsWith('END:VEVENT' . AbstractIcalEvent::CRLF, $iCalEvent);
        $this->assertContains('SUMMARY:' . $summary, $iCalEvent);
        $this->assertContains('STATUS:' . $statusInEventConverter, $iCalEvent);
    }
}
