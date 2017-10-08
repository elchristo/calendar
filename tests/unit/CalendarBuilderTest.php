<?php

namespace Elchristo\Calendar\Test;

use Elchristo\Calendar\Exception\InvalidArgumentException;
use Elchristo\Calendar\Service\Builder\CalendarBuilder;
use Elchristo\Calendar\Model\CalendarInterface;
use Elchristo\Calendar\Model\DefaultCalendar;
use Elchristo\Calendar\Service\Builder\SourceBuilder;
use Elchristo\Calendar\Model\Source\SourceInterface;
use Elchristo\Calendar\Model\Event\Collection;
use Elchristo\Calendar\Test\unit\Stub\TestCalendar;
use Elchristo\Calendar\Test\unit\Stub\TestSource;

class CalendarBuilderTest extends TestCase
{
    /* @var $eventsCollection Collection */

    /**
     * Test creation and initialization of calendar builder instance
     */
    public function testCanCreateAndInitCalendarBuilderInstance()
    {
        // given
        $builder = $this->getCalendarBuilder();

        // then
        $this->assertInstanceOf(CalendarBuilder::class, $builder);
        $this->assertInstanceOf(SourceBuilder::class, $builder->getSourceBuilder());
    }

    /**
     * Test to create a calendar which is not declared in configuration (default calendar instance expected)
     */
    public function testBuildDefaultCalendarByUndefinedName()
    {
        // given
        $builder = $this->getCalendarBuilder();
        $calendarName = 'NotConfiguredCalendarName';
        $expectedCalendarInterface = CalendarInterface::class;
        $expectedCalendarClass = DefaultCalendar::class;

        // when
        $calendar = $builder->build($calendarName);

        // then
        $this->assertInstanceOf($expectedCalendarInterface, $calendar);
        $this->assertInstanceOf($expectedCalendarClass, $calendar);
        $this->assertEquals($calendarName, $calendar->getName());
    }

    /**
     * Test expected Exception raised when trying to build calendar by classname not implementing CalendarInterface
     */
    public function testExpectedExceptionIsRaisedWhenTryingToBuildCalendarByInvalidClass()
    {
        // given
        $builder = $this->getCalendarBuilder();
        $invalidCalendarClassname = \stdClass::class;
        $this->expectException(InvalidArgumentException::class);

        // when
        $calendar = $builder->build($invalidCalendarClassname);
    }

    /**
     * Test to create calendar instance declared in config
     */
    public function testBuildCalendarDeclaredInContainer()
    {
        // given
        $builder = $this->getCalendarBuilder();
        $calendarNameInConfig = $expectedCalendarClassname = TestCalendar::class;

        // when
        $calendar = $builder->build($calendarNameInConfig);

        // then
        $this->assertInstanceOf(CalendarInterface::class, $calendar);
        $this->assertEquals(\get_class($calendar), $expectedCalendarClassname);
        $this->assertEquals($calendarNameInConfig, $calendar->getName());
    }

    /**
     * Test to retrieve calendar events source preconfigured in calendar class
     */
    public function testGetSourceFromPreconfiguredCalendar()
    {
        // given
        $builder = $this->getCalendarBuilder();
        $calendarName = TestCalendar::class;

        // when
        $calendar = $builder->build($calendarName);

        // then
        $sourceName = TestSource::class;
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
        $builder = $this->getCalendarBuilder();
        $calendarName = 'SimpleDefaultCalendar';
        $sourceName = TestSource::class;
        $sourceOptions = [];
        $expectedInterface = SourceInterface::class;

        // when
        $calendar = $builder->build($calendarName);
        $calendar->addSource($sourceName, $sourceOptions);
        $source = $calendar->getSource($sourceName);

        // then
        $this->assertInstanceOf($expectedInterface, $source);
    }

    /**
     * Test to add source with multiple options
     */
    public function testAddedSourceHasPassedOptions()
    {
        // given
        $builder = $this->getCalendarBuilder();
        $calendarName = 'SimpleDefaultCalendarToTestSourceWithOptions';
        $sourceName = TestSource::class;
        $sourceOptionsToAdd = [
            'option_string' => 'abc',
            'option_int' => 12345,
            'option_bool' => \true,
            'option_object' => new \DateTime,
            'option_array' => [
                'a',
                2,
                new \DateTime
            ]
        ];

        // when
        $calendar = $builder->build($calendarName);
        $calendar->addSource($sourceName, $sourceOptionsToAdd);
        $source = $calendar->getSource($sourceName);
        $options = $source->getOptions();

        // then
        $this->assertEquals($options, $sourceOptionsToAdd);
    }
}
