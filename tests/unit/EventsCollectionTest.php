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
        // given
        $eventA = new DefaultCalendarEvent(111, $this->events[111]);
        $eventB = new DefaultCalendarEvent(222, $this->events[222]);
        $eventC = new DefaultCalendarEvent(333, $this->events[333]);
        $eventD = new DefaultCalendarEvent(444, $this->events[444]);
        $eventE = new DefaultCalendarEvent(555, $this->events[555]);

        // when
        $this->collection
            ->add($eventA)
            ->add($eventB)
            ->add($eventC)
            ->set(12345, $eventD);

        $this->collection->set($eventE->getId(), $eventE);

        // then
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
     * Test to use "prefix_identifier" option for events in collection
     */
    public function testUseOptionEventIdentifierPrefix()
    {
        // given
        $eventA = new DefaultCalendarEvent(111, $this->events[111], [ 'prefix_identifier' => 'ID_prefix_' ]);
        $eventB = new DefaultCalendarEvent(222, $this->events[222], [ 'prefix_identifier' => 'another_prefix_' ]);
        $eventC = new DefaultCalendarEvent(333, $this->events[333]);

        // when
        $this->collection->add($eventA)->add($eventB)->add($eventC);

        // then
        $this->assertEquals($eventA, $this->collection->get('ID_prefix_111'));
        $this->assertEquals($eventB, $this->collection->get('another_prefix_222'));
        $this->assertEquals($eventC, $this->collection->get(333));
    }


    /**
     * Test if a collection contains added event object
     */
    public function testAddEventToCollection()
    {
        // given
        $event = new DefaultCalendarEvent(111, $this->events[111]);

        // when
        $this->collection->add($event);

        // then
        $this->assertTrue($this->collection->contains($event));
    }

    /**
     * Test to remove events from the collection (by identifier)
     */
    public function testCanRemoveEventFromCollectionByIdentifier()
    {
        // given
        $event = new DefaultCalendarEvent(222, $this->events[222]);

        // when
        $this->collection->add($event);
        $this->collection->remove(222);

        // then
        $this->assertFalse($this->collection->contains($event));
    }

        /**
     * Test to remove events from the collection (by object)
     */
    public function testCanRemoveEventFromCollectionByObject()
    {
        // given
        $event = new DefaultCalendarEvent(222, $this->events[222]);

        // when
        $this->collection->add($event);
        $this->collection->removeElement($event);

        // then
        $this->assertFalse($this->collection->contains($event));
    }

    /**
     * Test if a collection contains correct number of added events
     */
    public function testHasCorrectNumberOfEventsInCollection()
    {
        // given
        $eventA = new DefaultCalendarEvent(111, $this->events[111]);
        $eventB = new DefaultCalendarEvent(222, $this->events[222]);

        // when
        $this->collection->add($eventA);
        $this->collection->add($eventB);

        // then
        $this->assertCount(2, $this->collection);
    }


    /**
     * Test thet we can add events multiple timpes without duplication
     */
    public function testHasCorrectNumberOfEventsIfAddedMultipleTimes()
    {
        // given
        $eventA = new DefaultCalendarEvent(111, $this->events[111]);
        $eventB = new DefaultCalendarEvent(222, $this->events[222]);

        // when
        $this->collection->add($eventA);
        $this->collection->add($eventB);
        $this->collection->add($eventB);
        $this->collection->add($eventB);

        // then
        $this->assertCount(2, $this->collection);
    }

    /**
     * Test that we can add events into the collection and retrieve them afterwards
     */
    public function testCanGetCollectionAsArray()
    {
        // given
        $eventA = new DefaultCalendarEvent(111, $this->events[111]);
        $eventB = new DefaultCalendarEvent(666, $this->events[666]);

        // when
        $this->collection->add($eventA)->add($eventB);
        $collAsArray = $this->collection->toArray();

        // then
        $this->assertInternalType('array', $collAsArray);
        $this->assertArrayHasKey(111, $collAsArray);
        $this->assertArrayHasKey(666, $collAsArray);
        $this->assertInternalType('array', $collAsArray[111]);
        $this->assertEquals($this->events[111]['title'], $collAsArray[111]['title']);
        $this->assertInstanceOf('DateTime', $collAsArray[111]['start']);
        $this->assertEquals($this->events[666]['start'], $collAsArray[666]['start']);
    }
}