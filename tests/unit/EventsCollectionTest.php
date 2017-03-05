<?php
namespace Elchristo\Calendar\Test;

use Elchristo\Calendar\Model\Event\DefaultCalendarEvent;
use Elchristo\Calendar\Model\Event\Collection;

class EventsCollectionTest extends TestCase
{
    private $collection;
    private $events = [];

    public function _before()
    {
        $this->collection = new Collection();

        $this->events = [
            111 => [
                'title' => 'event A title',
                'titleShort' => 'event A title short',
                'start' => \DateTime::createFromFormat('dmY Hi', '17112017 1130'),
                'end' => \DateTime::createFromFormat('dmY Hi', '17112017 1345'),
                'alldayEvent' => false
            ],
            222 => [
                'title' => 'event B title',
                'alldayEvent' => true // time information to ignore
            ],
            333 => [
                'titleShort' => 'title long and short',
                'description' => 'event description',
                'start' => \DateTime::createFromFormat('dmY Hi', '02012017 0900'),
                'end' => \DateTime::createFromFormat('dmY Hi', '02012017 1800'),
                'alldayEvent' => 'true' // to be casted to TRUE
            ],
            444 => [
                'alldayEvent' => 0 // to be casted to FALSE
            ],
            555 => [
                'title' => 'event to test default "alldayevent" value.'
            ],
            666 => [
                'title' => 'event 666 title',
                'titleShort' => 'event 666 title short',
                'start' => \DateTime::createFromFormat('dmY Hi', '04092017 0715'),
                'end' => \DateTime::createFromFormat('dmY Hi', '04092017 0745'),
            ],
        ];

        parent::_before();
    }

    /**
     * Test that we can add events into the collection and retrieve them afterwards
     */
    public function testCanWriteInAndReadFromCollection()
    {
        // create events
        $eventA = new DefaultCalendarEvent(111, $this->events[111]);
        $eventB = new DefaultCalendarEvent(222, $this->events[222]);
        $eventC = new DefaultCalendarEvent(333, $this->events[333]);
        $eventD = new DefaultCalendarEvent(444, $this->events[444]);
        $eventE = new DefaultCalendarEvent(555, $this->events[555]);

        // adding 5 events to the collection
        $this->collection
            ->add($eventA)
            ->add($eventB)
            ->add($eventC)
            ->set(12345, $eventD);

        $this->collection->set($eventE->getId(), $eventE);

        // asserts
        $eventAFromCollection = $this->collection->get(111);
        $this->assertEquals($this->events[111]['title'], $eventAFromCollection->getTitle());
        $this->assertEquals($this->events[111]['titleShort'], $eventAFromCollection->getTitleShort());
        $this->assertEquals($this->events[111]['start'], $eventAFromCollection->getStart());
        $this->assertEquals($this->events[111]['end'], $eventAFromCollection->getEnd());
        $this->assertEquals(\false, $eventAFromCollection->isAlldayEvent());

        $eventBFromCollection = $this->collection->get(222);
        $this->assertEquals($this->events[222]['title'], $eventBFromCollection->getTitle());
        $this->assertEmpty($eventBFromCollection->getTitleShort());
        $this->assertEmpty($eventBFromCollection->getDescription());
        $this->assertEquals(\true, $eventBFromCollection->isAlldayEvent());

        $eventCFromCollection = $this->collection->get(333);
        $this->assertEquals($eventCFromCollection->getTitle(), $eventCFromCollection->getTitleShort());
        $this->assertEquals(\true, $eventCFromCollection->isAlldayEvent(), 'Event property "alldayevent" not casted correctly to boolean.');

        $eventDFromCollection = $this->collection->get(12345);
        $this->assertEquals(\false, $eventDFromCollection->isAlldayEvent(), 'Event property "alldayevent" not casted correctly to boolean.');

        $eventEFromCollection = $this->collection->get(555);
        $this->assertEquals(\false, $eventEFromCollection->isAlldayEvent(), 'Event property "alldayevent" should be FALSE by default.');
    }

    /**
     * Test to remove events from the collection (by object and by identifier)
     */
    public function testCanRemoveEventFromCollection()
    {
        $eventA = new DefaultCalendarEvent(111, $this->events[111]);
        $eventB = new DefaultCalendarEvent(222, $this->events[222]);
        $this->collection->add($eventA)->add($eventB);

        $this->assertTrue($this->collection->contains($eventA));
        $this->assertTrue($this->collection->contains($eventB));

        $this->collection->removeElement($eventA);
        $this->assertFalse($this->collection->contains($eventA));
        $this->collection->remove(222);
        $this->assertFalse($this->collection->contains($eventB));
    }

    /**
     * Test if a collection contains added event object
     */
    public function testContainsAddedEvent()
    {
        $event = new DefaultCalendarEvent(111, $this->events[111]);
        $this->collection->add($event);
        $this->assertTrue($this->collection->contains($event));
    }

    /**
     * Test if a collection contains added event objetc
     */
    public function testCountNumberOfEventsInCollection()
    {
        $eventA = new DefaultCalendarEvent(111, $this->events[111]);
        $this->collection->add($eventA);
        $this->assertTrue($this->collection->contains($eventA));
        $this->assertCount(1, $this->collection);

        $eventB = new DefaultCalendarEvent(222, $this->events[222]);
        $this->collection->add($eventB);
        $this->assertCount(2, $this->collection);
        $this->collection->add($eventB); // add same event again
        $this->assertCount(2, $this->collection);

    }

    /**
     * Test that we can add events into the collection and retrieve them afterwards
     */
    public function testCanGetCollectionAsArray()
    {
        // create events
        $eventA = new DefaultCalendarEvent(111, $this->events[111]);
        $eventB = new DefaultCalendarEvent(666, $this->events[666]);

        // adding 5 events to the collection
        $this->collection->add($eventA)->add($eventB);

        $collAsArray = $this->collection->toArray();
        $this->assertInternalType('array', $collAsArray);
        $this->assertArrayHasKey(111, $collAsArray);
        $this->assertArrayHasKey(666, $collAsArray);
        $this->assertInternalType('array', $collAsArray[111]);
        $this->assertEquals($this->events[111]['title'], $collAsArray[111]['title']);
        $this->assertInstanceOf('DateTime', $collAsArray[111]['start']);
        $this->assertEquals($this->events[666]['start'], $collAsArray[666]['start']);
    }
}