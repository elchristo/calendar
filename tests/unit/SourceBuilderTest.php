<?php
namespace Elchristo\Calendar\Test;

use Elchristo\Calendar\Model\Event\Collection;
use Elchristo\Calendar\Model\Source\SourceInterface;
use Elchristo\Calendar\Service\Builder\SourceBuilder;
use Elchristo\Calendar\Test\unit\Stub\TestSource;
use Elchristo\Calendar\Exception\InvalidArgumentException;

class SourceBuilderTest extends TestCase
{
    /**
     * Test to build source by its name in configuration
     */
    public function testBuildSourceByConfig()
    {
        // given
        $builder = self::getServiceContainer()->get(SourceBuilder::class);
        $sourceName = TestSource::class;
        $sourceInterface = SourceInterface::class;

        // when
        $source = $builder->build($sourceName);

        // then
        $this->assertInstanceOf($sourceInterface, $source, 'Cannot create source by name (in config).');
    }

    /**
     * Test to build source by classname
     */
    public function testBuildSourceByClassname()
    {
        // given
        $builder = self::getServiceContainer()->get(SourceBuilder::class);
        $sourceName = unit\Stub\TestSource::class;
        $sourceInterface = SourceInterface::class;

        // when
        $source = $builder->build($sourceName);

        // then
        $this->assertInstanceOf($sourceInterface, $source, 'Cannot create source by classname.');
    }

    /**
     * Test to initialize source identifier by configured classname
     */
    public function testBuildSourceIdentifierWithSourceClassname()
    {
        // given
        $builder = self::getServiceContainer()->get(SourceBuilder::class);
        $sourceName = TestSource::class;
        $expectedName = 'elchristocalendartestunitstubtestsource';

        // when
        $source = $builder->build($sourceName);

        // then
        $this->assertEquals($expectedName, $source->getIdentifier());
    }

    /**
     * Test to retrieve event collection from source
     */
    public function testCanRetrieveEventsCollection()
    {
        // given
        $builder = self::getServiceContainer()->get(SourceBuilder::class);
        $source = $builder->build(TestSource::class); // stub class unit\Stub\TestSource
        $expected = Collection::class;

        // when
        $eventsCollection = $source->getEvents();

        // then
        $this->assertInstanceOf($expected, $eventsCollection);
    }

    /**
     * Test to retrieve event collection from source
     */
    public function testRetrieveEventsCollectionFromSource()
    {
        // given
        $builder = self::getServiceContainer()->get(SourceBuilder::class);
        $source = $builder->build(TestSource::class); // stub class unit\Stub\TestSource

        // when
        $eventsCollection = $source->getEvents();
        $iterator = $eventsCollection->getIterator();

        // then
        $this->assertCount(2, $eventsCollection->toArray());
        $this->assertEquals(2, $eventsCollection->count());
        $this->assertEquals(2, $iterator->count());

        // test expected event property values
        $event = $iterator->current();
        $this->assertEquals($event->getId(), 123);
        $this->assertEquals($event->getTitle(), 'long event title', 'Cannot read event property "title".');
        $this->assertEquals($event->getTitleShort(), 'short title', 'Cannot read event property "titleShort".');
        $this->assertEquals($event->getDescription(), 'this is the first event added to the calendar events source', 'Cannot read event property "description".');
        $this->assertEquals($event->isAllDayEvent(), true, 'Event should be an allday event.');
        $this->assertEquals($event->getStart()->format('dmY'), '03112017', 'Event has unexpected start date.');
        $this->assertEquals($event->getEnd()->format('dmY'), '05112017', 'Event has unexpected end date.');

        $event2 = $iterator->offsetGet(456);
        $this->assertEquals($event2->getId(), 456);
    }

    /**
     * Test that expected InvalidArgumentException is raised when trying to build unknown source
     */
    public function testExpectedExceptionIsRaisedWhenBuildingNonExistingCalendarSource()
    {
        // given
        $builder = self::getServiceContainer()->get(SourceBuilder::class);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Calendar source class AnyNonExistingClassName does not exist.');

        // when
        $source = $builder->build('AnyNonExistingClassName');

        // then
    }

    /**
     * Test that expected InvalidArgumentException is raised when trying to build invalid source
     */
    public function testExpectedExceptionIsRaisedWhenBuildingInvalidCalendarSource()
    {
        // given
        $builder = self::getServiceContainer()->get(SourceBuilder::class);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Declared calendar source 'stdClass' needs to implement Elchristo\Calendar\Model\Source\SourceInterface.");

        // when
        $source = $builder->build(\stdClass::class);

        // then
    }
}