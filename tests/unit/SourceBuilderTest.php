<?php
namespace Elchristo\Calendar\Test;

use Elchristo\Calendar\Service\Config\Config;
use Elchristo\Calendar\Model\Event\Collection;
use Elchristo\Calendar\Model\Source\SourceInterface;
use Elchristo\Calendar\Service\Builder\SourceBuilderFactory;

class SourceBuilderTest extends TestCase
{
    private function getConfigProvider()
    {
        return new Config(self::getConfig()['elchristo']['calendar']);
    }

    /**
     * Test to build source by its name in configuration
     */
    public function testBuildSourceByConfig()
    {
        // given
        $builder = SourceBuilderFactory::create($this->getConfigProvider());
        $sourceName = 'TestSource'; // stub class unit\Stub\TestSource
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
        $builder = SourceBuilderFactory::create($this->getConfigProvider());
        $sourceName = unit\Stub\TestSource::class;
        $sourceInterface = SourceInterface::class;

        // when
        $source = $builder->build($sourceName);

        // then
        $this->assertInstanceOf($sourceInterface, $source, 'Cannot create source by classname.');
    }

        /**
     * Test to retrieve event collection from source
     */
    public function testCanRetrieveEventsCollection()
    {
        // given
        $builder = SourceBuilderFactory::create($this->getConfigProvider());
        $source = $builder->build('TestSource'); // stub class unit\Stub\TestSource
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
        $builder = SourceBuilderFactory::create($this->getConfigProvider());
        $source = $builder->build('TestSource'); // stub class unit\Stub\TestSource
        $eventsCollection = $source->getEvents();

        // when
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
}