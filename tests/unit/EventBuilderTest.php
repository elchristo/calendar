<?php

namespace Elchristo\Calendar\Test;

use Elchristo\Calendar\Service\Config\Config;
use Elchristo\Calendar\Service\Builder\EventBuilder;
use Elchristo\Calendar\Model\Event\DefaultCalendarEvent;

class EventBuilderTest extends TestCase
{
    private function getConfigProvider()
    {
        return new Config(self::getConfig()['elchristo']['calendar']);
    }

    /**
     * Test existance of mandatory event builder class attributes
     */
    public function testEventBuilderClassAttributes()
    {
        $this->assertClassHasStaticAttribute('instance', EventBuilder::class);
        $this->assertClassHasAttribute('configService', EventBuilder::class);
        $this->assertClassHasAttribute('registeredEvents', EventBuilder::class);
        $this->assertClassHasAttribute('registeredColors', EventBuilder::class);
        $this->assertClassHasAttribute('registeredColorStrategies', EventBuilder::class);
    }

    /**
     * Test to build a default calendar event by any name (not declared in configuration)
     * and check if it is initialized with all mandatory information
     */
    public function testCanBuildDefaultCalendarEventByNotDeclaredName()
    {
        $configProvider = $this->getConfigProvider();
        $builder = EventBuilder::getInstance($configProvider);
        $event = $builder->build('SomeUndefinedCalendarEvent');

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
        $configProvider = $this->getConfigProvider();
        $builder = EventBuilder::getInstance($configProvider);
        $registeredEvents = $configProvider->getRegisteredEvents();
        $eventName = 'TestEventBasic';
        $eventNameInConfig = (isset($registeredEvents[$eventName])) ? $eventName : null;
        $this->assertNotNull($eventNameInConfig, 'Event declaration missing in configuration');
        $event = $builder->build($eventName);

        $this->assertInstanceOf($registeredEvents[$eventName], $event);
    }

    /**
     * Test to build a calendar event by its classname
     */
    public function testCanBuildEventByClassname()
    {
        $configProvider = $this->getConfigProvider();
        $builder = EventBuilder::getInstance($configProvider);
        $eventClassname = unit\Stub\TestEventBasic::class;
        $event = $builder->build($eventClassname);

        $this->assertInstanceOf($eventClassname, $event);
    }

    /**
     * Test to set and get individual event attributes,
     * either by setter and getter methods or by magic __set/__get method
     */
    public function testCanSetAndGetCalendarEventAttributes()
    {
        $configProvider = $this->getConfigProvider();
        $builder = EventBuilder::getInstance($configProvider);
        $eventName = 'TestEventWithAttributes';
        $event = $builder->build($eventName);

        $event->setAttributeA('value a');
        $this->assertEquals($event->getAttributeA(), 'value a');

        $event->setAttributeB(array('1', '2', '3'));
        $this->assertEquals($event->getAttributeB(), array('1', '2', '3'));

        $event->setAttributeC(123);
        $this->assertEquals($event->getAttributeC(), 123);
        $this->assertInternalType('int', $event->getAttributeC());

        $event->setUndefinedAttribute('value for undefined attribute');
        $this->assertNull($event->getUndefinedAttribute());
    }
}
