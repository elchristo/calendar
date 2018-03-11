<?php

namespace Elchristo\Calendar\Test;

use Elchristo\Calendar\Service\Builder\EventBuilder;
use Elchristo\Calendar\Model\Event\DefaultCalendarEvent;
use Elchristo\Calendar\Test\unit\Stub\TestEventBasic;
use Elchristo\Calendar\Test\unit\Stub\TestEventWithAttributes;
use Elchristo\Calendar\Service\Color\DefaultColorStrategy;
use Elchristo\Calendar\Service\Color\ColorStrategyInterface;
use Elchristo\Calendar\Test\unit\Stub\TestColorStrategy;

class EventBuilderTest extends TestCase
{
    /**
     * Test existance of mandatory event builder class attributes
     */
    public function testContainsMandatoryClassAttributes()
    {
        // given
        $eventBuilderClass = EventBuilder::class;

        // then
        $this->assertClassHasStaticAttribute('instance', $eventBuilderClass);
    }

    /**
     * Test existance of mandatory attributes and values in default calendar event
     */
    public function testDerfaultCalendarEventContainsValidMandatoryAttributes()
    {
        // given
        $eventName = DefaultCalendarEvent::class;

        // when
        $event = self::getServiceContainer()->build($eventName);

        // then
        $this->assertInstanceOf(DefaultCalendarEvent::class, $event);
        $this->assertNotEmpty($event->getId(), 'Event identifier cannot be empty');
        $this->assertInstanceOf(\DateTime::class, $event->getStart());
        $this->assertInstanceOf(\DateTime::class, $event->getEnd());
        $this->assertInstanceOf(\DateTime::class, $event->getCreationDate());
        $this->assertInstanceOf(\DateTime::class, $event->getLastModificationDate());
        $this->assertFalse($event->isAlldayEvent());
    }

    /**
     * Test to build a default calendar event by any name (not declared as service in configuration)
     */
    public function testCanBuildValidDefaultCalendarEventByUndefinedName()
    {
        // given
        $eventName = 'SomeUndefinedCalendarEvent'; // name must end with "CalendarEvent"
        $eventName2 = 'DefaultCalendarEvent'; // name must end with "CalendarEvent"

        // when
        $event = self::getServiceContainer()->build($eventName);
        $event2 = self::getServiceContainer()->build($eventName2);

        // then
        $this->assertInstanceOf(DefaultCalendarEvent::class, $event);
        $this->assertInstanceOf(DefaultCalendarEvent::class, $event2);
    }

    /**
     * Test to build a calendar event by servicename declared in configuration
     */
    public function testCanBuildEventByServiceName()
    {
        // given
        $eventClassName = TestEventBasic::class;

        // when
        $event = self::getServiceContainer()->build($eventClassName);

        // then
        $this->assertInstanceOf($eventClassName, $event, 'Event is not declared as service in configuration');
    }

    /**
     * Test to set and get individual event attributes,
     * either by setter and getter methods or by magic __set/__get method
     */
    public function testCanSetAndGetCalendarEventAttributes()
    {
        // given
        $eventName = TestEventWithAttributes::class;

        // when
        $event = self::getServiceContainer()->build($eventName);
        $event->setAttributeA('value a');
        $event->setAttributeB(array('1', '2', '3'));
        $event->setAttributeC(123);
        $event->setUndefinedAttribute('value for undefined attribute');

        // then
        $this->assertEquals($event->getAttributeA(), 'value a');
        $this->assertEquals($event->getAttributeB(), array('1', '2', '3'));
        $this->assertEquals($event->getAttributeC(), 123);
        $this->assertInternalType('int', $event->getAttributeC());
        $this->assertNull($event->getUndefinedAttribute());
    }

    /**
     * Test that event implementing ColoredEventInterface has DefaultColorStrategy by default
     */
    public function testThatEventImplementingColoredEventInterfaceHasDefaultColorStrategy()
    {
        // given
        $eventName = TestEventBasic::class;
        $expectedColorStrategy = DefaultColorStrategy::class;

        // when
        $event = self::getServiceContainer()->build($eventName);
        $colorStrategy = $event->getColorStrategy();

        // then
        $this->assertInstanceOf($expectedColorStrategy, $colorStrategy, 'Event implements ColoredEventInterface but has no DefaultColorStrategy.');
    }

    /**
     * Test that color strategy can be changed on an event implementing ColoredEventInterface
     */
    public function testCanChangeColorStrategyOnEventImplementingColoredEventInterface()
    {
        // given
        $eventName = TestEventBasic::class;
        $expectedColorStrategyInterface = ColorStrategyInterface::class;
        $expectedColorStrategyAfterChange = TestColorStrategy::class;

        // when
        $event = self::getServiceContainer()->build($eventName);
        $newColorStrategy = self::getServiceContainer()->get($expectedColorStrategyAfterChange);
        $event->setColorStrategy($newColorStrategy);
        $colorStrategy = $event->getColorStrategy();

        // then
        $this->assertInstanceOf($expectedColorStrategyInterface, $colorStrategy, "Event must implement {$expectedColorStrategyInterface}.");
        $this->assertInstanceOf($expectedColorStrategyAfterChange, $colorStrategy, "Event should have color strategy {$expectedColorStrategyAfterChange}.");
    }

    /**
     * Test that color strategy class is injected correctly by using its service alias
     * defined in service container configuration
     */
    public function testCanInjectColorStrategyByAlias()
    {
        // given
        $eventName = TestEventBasic::class;
        $eventOptions = [ 'color_strategy' => 'MyFirstColorStrategyAlias' ];
        $expectedColorStrategy = TestColorStrategy::class;

        // when
        $event = self::getServiceContainer()->build($eventName, $eventOptions);
        $colorStrategy = $event->getColorStrategy();

        // then
        $this->assertInstanceOf($expectedColorStrategy, $colorStrategy, "Event should have color strategy {$expectedColorStrategy}.");
    }
}
