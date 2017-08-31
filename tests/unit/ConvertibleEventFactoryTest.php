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

    /**
     * Test to build a convertible event and convert it into FullCalendar JSON format
     */
    public function testCanCreateEventAndConvertItToFullCalendarJson()
    {
        // given
        $configProvider = $this->getConfigProvider();
        $builder = EventBuilder::getInstance($configProvider);
        $event = $builder->build('SomeCalendarEventForFullCalendarConversionTest');
        $factory = $this->getFactory();

        // when
        $fcConverter = $factory->build($event, 'FullCalendar');

        // then
        $this->assertInstanceOf(ConvertibleEventInterface::class, $fcConverter);
        $this->assertJson($fcConverter->convert());
    }


    /**
     * Test to build a convertible FullCalendar event
     */
    public function testCanBuildConvertibleFullCalendarEvent()
    {
        // given
        $builder = EventBuilder::getInstance($this->getConfigProvider());
        $event = $builder->build('TestCalendarFcEventToBeConverted');
        $description = 'fullcalendar event description.';
        $event->setDescription($description);

        // when
        $fullcalendarEventConverter = $this->getFactory()->build($event, 'FullCalendar');

        // then
        $this->assertInstanceOf(ConvertibleEventInterface::class, $fullcalendarEventConverter);
    }

    /**
     * Test to convert event into FullCalendar event with correct attribute values
     */
    public function testCanConvertIntoFullCalendarEventContainingCorrectAttributeValues()
    {
        // given
        $expectedTitle = 'fullcalendar event title.';
        $expectedDescription = 'fullcalendar event description.';

        $builder = EventBuilder::getInstance($this->getConfigProvider());
        $event = $builder->build('TestCalendarEventToBeConvertedIntoFullCalendarJson');
        $event
            ->setTitle($expectedTitle)
            ->setDescription($expectedDescription);

        // when
        $fullCalendarEventConverter = $this->getFactory()->build($event, 'FullCalendar');
        $fullCalendarJson = $fullCalendarEventConverter->convert();

        // then
        $this->assertContains($expectedTitle, $fullCalendarJson);
        $this->assertContains($expectedDescription, $fullCalendarJson);
    }

    /**
     * Test to convert event into FullCalendar event with all its specific attributes
     */
    public function testCreatedFullCalendarEventContainsAllAttributes()
    {
        // given
        $expectedAttributes = [
            'id', 'idBySource', 'title', 'titleDetails', 'published', 'description', 'start', 'end', 'allDay', 'editable'
        ];

        $builder = EventBuilder::getInstance($this->getConfigProvider());
        $event = $builder->build('TestCalendarEventToBeConvertedIntoFullCalendarJson');

        // when
        $fullCalendarEventConverter = $this->getFactory()->build($event, 'FullCalendar');
        $fullCalendarJson = $fullCalendarEventConverter->convert();

        // then
        $eventAsArray = \json_decode($fullCalendarJson, true);
        foreach ($expectedAttributes as $attr) {
            $this->assertArrayHasKey($attr, $eventAsArray);
        }
    }

    /**
     * Test to convert event into FullCalendar event with correct ISO datetime format
     */
    public function testCreatedFullCalendarEventHasCorrectIsoStartAndEndDate()
    {
        // given
        $expectedIsoDatetimePattern = '/^([1-9]\d{3})-(\d{2})-(\d{2})T(\d{2})\:(\d{2})\:(\d{2})$/'; // Y-m-d\TH:i:s
        $builder = EventBuilder::getInstance($this->getConfigProvider());
        $eventWithoutStartAndEnd = $builder->build('TestCalendarEventWithoutStartAndEnd');
        $eventWithStartAndEnd = $builder->build('TestCalendarEventWithStartAndEnd');

        $start = \DateTime::createFromFormat('YmdHi', '201708310915');
        $end = \DateTime::createFromFormat('YmdHi', '201708312200');
        $eventWithStartAndEnd
            ->setStart($start)
            ->setEnd($end);

        // when
        $fullCalendarEventConverter = $this->getFactory()->build($eventWithoutStartAndEnd, 'FullCalendar');
        $fullCalendarJson = $fullCalendarEventConverter->convert();
        $fullCalendarEventConverter2 = $this->getFactory()->build($eventWithStartAndEnd, 'FullCalendar');
        $fullCalendarJson2 = $fullCalendarEventConverter2->convert();

        // then
        $eventAsArray = \json_decode($fullCalendarJson, true);
        $this->assertRegExp($expectedIsoDatetimePattern, $eventAsArray['start']);
        $this->assertRegExp($expectedIsoDatetimePattern, $eventAsArray['end']);

        $eventAsArray = \json_decode($fullCalendarJson2, true);
        $this->assertRegExp($expectedIsoDatetimePattern, $eventAsArray['start']);
        $this->assertEquals('2017-08-31T09:15:00', $eventAsArray['start']);
        $this->assertRegExp($expectedIsoDatetimePattern, $eventAsArray['end']);
        $this->assertEquals('2017-08-31T22:00:00', $eventAsArray['end']);
    }

    /**
     * Test to convert event into FullCalendar event with default "allDay" value FALSE
     */
    public function testCreatedFullCalendarEventWithDefaultAllDayValueFalseWhenNoStartAndEndSpecified()
    {
        // given
        $builder = EventBuilder::getInstance($this->getConfigProvider());
        $event = $builder->build('TestCalendarEventToBeConvertedIntoFullCalendarJson');

        // when
        $fullCalendarEventConverter = $this->getFactory()->build($event, 'FullCalendar');
        $fullCalendarJson = $fullCalendarEventConverter->convert();

        // then
        $eventAsArray = \json_decode($fullCalendarJson, true);
        $this->assertFalse($eventAsArray['allDay']);
    }
}
