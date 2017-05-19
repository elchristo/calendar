<?php

namespace Elchristo\Calendar\Test;

use Elchristo\Calendar\Service\Builder\CalendarBuilder;
use Elchristo\Calendar\Service\Builder\CalendarBuilderFactory;
use Elchristo\Calendar\Model\CalendarInterface;
use Elchristo\Calendar\Service\Builder\SourceBuilder;
use Elchristo\Calendar\Model\Source\SourceInterface;
use Elchristo\Calendar\Model\Event\Collection;

class CalendarBuilderTest extends TestCase
{
    /* @var $eventsCollection  Collection */

    /**
     * Test creation and initialization of calendar builder instance
     */
    public function testCanCreateAndInitInstance()
    {
        // given
        $builder = CalendarBuilderFactory::create(self::getConfig());

        // then
        $this->assertInstanceOf(CalendarBuilder::class, $builder);
        $this->assertInstanceOf(SourceBuilder::class, $builder->getSourceBuilder());
    }

    /**
     * Test to create a calendar which is not declared in configuration (default calendar instance expected)
     */
    public function testBuildUndefinedCalendar()
    {
        // given
        $builder = CalendarBuilderFactory::create(self::getConfig());
        $calendarName = 'NotConfiguredCalendarName';
        $calendarInterface = CalendarInterface::class;

        // when
        $calendar = $builder->build($calendarName);

        // then
        $this->assertInstanceOf($calendarInterface, $calendar);
        $this->assertEquals($calendarName, $calendar->getName());
    }

    /**
     * Test to create calendar instance declared in config
     */
    public function testBuildCalendarDeclaredInConfig()
    {
        // given
        $config = self::getConfig();
        $builder = CalendarBuilderFactory::create($config);
        $calendarNameInConfig = 'TestCalendar';
        $calendarClassInConfig = $config['elchristo']['calendar']['calendars'][$calendarNameInConfig];

        // when
        $calendar = $builder->build($calendarNameInConfig);

        // then
        $this->assertInstanceOf(CalendarInterface::class, $calendar);
        $this->assertEquals(\get_class($calendar), $calendarClassInConfig);
        $this->assertEquals($calendarNameInConfig, $calendar->getName());
    }

    /**
     * Test to retrieve calendar events source preconfigured in calendar class
     */
    public function testGetSourceFromPreconfiguredCalendar()
    {
        // given
        $builder = CalendarBuilderFactory::create(self::getConfig());
        $calendarName = 'TestCalendar';

        // when
        $calendar = $builder->build($calendarName);

        // then
        $sourceName = 'TestSource';
        $this->assertTrue($calendar->hasSource($sourceName), "Calendar has no source with name {$sourceName}");
        $source = $calendar->getSource($sourceName);
        $this->assertInstanceOf(SourceInterface::class, $source);
    }

    /**
     * Test to
     *  - add an events source to calendar
     *  - check expected event property values
     */
    public function testCanAddKnownSourceToCalendar()
    {
        // given
        $builder = CalendarBuilderFactory::create(self::getConfig());
        $calendarName = 'SimpleDefaultCalendar';
        $sourceName = 'TestSource';
        $sourceOptions = [];

        // when
        $calendar = $builder->build($calendarName);
        $calendar->addSource($sourceName, $sourceOptions);
        $source = $calendar->getSource($sourceName);

        // then
        $this->assertInstanceOf(SourceInterface::class, $source);
    }
}
