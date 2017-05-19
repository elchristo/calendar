<?php

namespace Elchristo\Calendar\Test;

use Elchristo\Calendar\Service\Config\Config;
use Elchristo\Calendar\Service\Builder\EventBuilder;
use Elchristo\Calendar\Model\Event\DefaultCalendarEvent;

class EventBuilderTest extends TestCase
{
    /**
     * @return Config Default calendar configuration for tests
     */
    private function getConfigProvider()
    {
        return new Config(self::getConfig()['elchristo']['calendar']);
    }

    /**
     * Test existance of mandatory event builder class attributes
     */
    public function testContainsMandatoryClassAttributes()
    {
        // given
        $eventBuilderClass = EventBuilder::class;

        // then
        $this->assertClassHasStaticAttribute('instance', $eventBuilderClass);
        $this->assertClassHasAttribute('configService', $eventBuilderClass);
        $this->assertClassHasAttribute('registeredEvents', $eventBuilderClass);
        $this->assertClassHasAttribute('registeredColors', $eventBuilderClass);
        $this->assertClassHasAttribute('registeredColorStrategies', $eventBuilderClass);
    }

    /**
     * Test to build a default calendar event by any name (not declared in configuration)
     * and check if it is initialized with all mandatory information
     */
    public function testCanBuildDefaultCalendarEventByNotDeclaredName()
    {
        // given
        $configProvider = $this->getConfigProvider();
        $builder = EventBuilder::getInstance($configProvider);

        // when
        $event = $builder->build('SomeUndefinedCalendarEvent');

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
     * Test to build a calendar event by a name declared in configuration
     */
    public function testCanBuildEventByNameInConfig()
    {
        // given
        $configProvider = $this->getConfigProvider();
        $builder = EventBuilder::getInstance($configProvider);
        $registeredEvents = $configProvider->getRegisteredEvents();
        $eventName = 'TestEventBasic';
        $eventNameInConfig = (isset($registeredEvents[$eventName])) ? $eventName : null;

        // when
        $event = $builder->build($eventName);

        // then
        $this->assertNotNull($eventNameInConfig, 'Event declaration missing in configuration');
        $this->assertInstanceOf($registeredEvents[$eventName], $event);
    }

    /**
     * Test to build a calendar event by its classname
     */
    public function testCanBuildEventByClassname()
    {
        // given
        $configProvider = $this->getConfigProvider();
        $builder = EventBuilder::getInstance($configProvider);
        $eventClassname = unit\Stub\TestEventBasic::class;

        // when
        $event = $builder->build($eventClassname);

        // then
        $this->assertInstanceOf($eventClassname, $event);
    }

    /**
     * Test to set and get individual event attributes,
     * either by setter and getter methods or by magic __set/__get method
     */
    public function testCanSetAndGetCalendarEventAttributes()
    {
        // given
        $configProvider = $this->getConfigProvider();
        $builder = EventBuilder::getInstance($configProvider);
        $eventName = 'TestEventWithAttributes';

        // when
        $event = $builder->build($eventName);
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
}
