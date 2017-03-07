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
        $builder = CalendarBuilderFactory::create(self::getConfig());
        $this->assertInstanceOf(CalendarBuilder::class, $builder);
        $this->assertInstanceOf(SourceBuilder::class, $builder->getSourceBuilder());
    }

    /**
     * Test to create a calendar which is not declared in configuration (default calendar instance expected)
     */
    public function testBuildUndefinedCalendar()
    {
        $builder = CalendarBuilderFactory::create(self::getConfig());
        $calendarName = 'NotConfiguredCalendarName';
        $calendar = $builder->build($calendarName);

        $this->assertInstanceOf(CalendarInterface::class, $calendar);
        $this->assertEquals($calendarName, $calendar->getName());
    }

    /**
     * Test to create calendar instance declared in config
     */
    public function testBuildCalendarDeclaredInConfig()
    {
        $config = self::getConfig();
        $builder = CalendarBuilderFactory::create($config);
        $calendarNameInConfig = 'TestCalendar';
        $calendar = $builder->build($calendarNameInConfig);

        $this->assertInstanceOf(CalendarInterface::class, $calendar);
        $this->assertEquals(\get_class($calendar), $config['elchristo']['calendar']['calendars'][$calendarNameInConfig]);
        $this->assertEquals($calendarNameInConfig, $calendar->getName());
    }

    /**
     * Test to retrieve calendar events source preconfigured in calendar class
     */
    public function testGetSourceFromPreconfiguredCalendar()
    {
        // build preconfigured calendar
        $builder = CalendarBuilderFactory::create(self::getConfig());
        $calendarName = 'TestCalendar';
        $calendar = $builder->build($calendarName);

        // test if source exists and retrieve it
        $sourceName = 'TestSource';
        $this->assertTrue($calendar->hasSource($sourceName), "Calendar has no source with name {$sourceName}");
        $source = $calendar->getSource($sourceName);
        $this->assertInstanceOf(SourceInterface::class, $source);
    }

    /**
     * Test to
     *  - add an events source to calendar
     *  - check number of events
     *  - check expected event property values
     */
    public function testAddSourceToCalendarAndCountEvents()
    {
        $builder = CalendarBuilderFactory::create(self::getConfig());
        $calendarName = 'SimpleDefaultCalendar';
        $sourceName = 'TestSource';
        $sourceOptions = [];

        // add source to empty calendar
        $calendar = $builder->build($calendarName);
        $calendar->addSource($sourceName, $sourceOptions);
        $source = $calendar->getSource($sourceName);
        $this->assertInstanceOf(SourceInterface::class, $source);
    }
}
