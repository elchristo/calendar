<?php

namespace Elchristo\Calendar\Test;

use Elchristo\Calendar\Converter\ConvertibleEventInterface;
use Elchristo\Calendar\Converter\ConvertibleEventFactory;
use Elchristo\Calendar\Converter\Ical\Event\AbstractIcalEvent;
use Elchristo\Calendar\Model\Event\Collection;
use Elchristo\Calendar\Service\Config\Config;
use Elchristo\Calendar\Converter\Converter;
use Elchristo\Calendar\Service\Builder\EventBuilder;
use Elchristo\Calendar\Service\Builder\SourceBuilderFactory;

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
     * Test to convert a calendar into given converter classname
     */
    public function testCanConvertCalendarByGivenConverterClassname()
    {
        // given
        $config = $this->getConfigProvider();
        $sourceBuilder = SourceBuilderFactory::create($config);
        $calendar = new unit\Stub\TestCalendar($sourceBuilder, new Collection());
        $jsonConverterClassname = \Elchristo\Calendar\Converter\Json\Json::class;

        // when
        $expected = Converter::convert($calendar, $jsonConverterClassname);

        // then
        $this->assertJson($expected);
    }

    /**
     * Test to convert a calendar into given converter name (in config)
     */
    public function testCanConvertCalendarByGivenConverterName()
    {
        // given
        $config = $this->getConfigProvider();
        $sourceBuilder = SourceBuilderFactory::create($config);
        $calendar = new unit\Stub\TestCalendar($sourceBuilder, new Collection());
        $calendar->setConfig($config);
        $jsonConverterName = 'json';

        // when
        $expected = Converter::convert($calendar, $jsonConverterName);

        // then
        $this->assertJson($expected);
    }

    /**
     * Test to build a convertible event and convert it to JSON
     */
    public function testCanCreateEventAndConvertItToJson()
    {
        // given
        $configProvider = $this->getConfigProvider();
        $builder = EventBuilder::getInstance($configProvider);
        $event = $builder->build('SomeUndefinedCalendarEvent');
        $factory = $this->getFactory();

        // when
        $jsonConverter = $factory->build($event, 'json');

        // then
        $this->assertInstanceOf(ConvertibleEventInterface::class, $jsonConverter);
        $this->assertJson($jsonConverter->convert());
    }

    /**
     * Test to build a convertible ical event
     */
    public function testCanBuildConvertibleIcalEvent()
    {
        // given
        $builder = EventBuilder::getInstance($this->getConfigProvider());
        $event = $builder->build('TestCalendarEventToBeConverted');
        $event->setSpecialAttribute('summary in special event attribut');

        // when
        $iCalEventConverter = $this->getFactory()->build($event, 'ical');

        // then
        $this->assertInstanceOf(ConvertibleEventInterface::class, $iCalEventConverter);
    }

    /**
     * Test to convert event into iCal string
     */
    public function testCanConvertCalendarEventToIcalString()
    {
        // given
        $beginEvent = 'BEGIN:VEVENT';
        $endEvent = 'END:VEVENT' . AbstractIcalEvent::CRLF;
        $summary = 'SUMMARY:summary in special event attribut';
        $statusInEventConverter = 'STATUS:TENTATIVE';

        $builder = EventBuilder::getInstance($this->getConfigProvider());
        $event = $builder->build('TestCalendarEventToBeConverted');
        $event->setSpecialAttribute($summary);

        // when
        $iCalEventConverter = $this->getFactory()->build($event, 'ical');
        $iCalEvent = $iCalEventConverter->convert();

        // then
        $this->assertStringStartsWith($beginEvent, $iCalEvent);
        $this->assertStringEndsWith($endEvent, $iCalEvent);
        $this->assertContains($summary, $iCalEvent);
        $this->assertContains($statusInEventConverter, $iCalEvent);
    }
}
