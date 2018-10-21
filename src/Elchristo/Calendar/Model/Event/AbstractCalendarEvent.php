<?php

namespace Elchristo\Calendar\Model\Event;

use \DateTime;
use Elchristo\Calendar\Model\Event\CalendarEventInterface;

/**
 * Abstract calendar event
 */
abstract class AbstractCalendarEvent implements CalendarEventInterface
{
    const DEFAULT_IS_ALLDAY_EVENT = false;
    const DEFAULT_IS_PUBLIC_EVENT = true;

    /** @var int|string Unique event identifier (prefixed event id) */
    protected $uid;

    /** @var int|string Event identifier */
    protected $id;

    /** @var string */
    protected $title;

    /** @var string */
    protected $titleShort;

    /** @var string */
    protected $description;

    /** @var Datetime */
    protected $start = null;

    /** @var Datetime */
    protected $end = null;

    /** @var DateTime */
    protected $created;

    /** @var DateTime */
    protected $lastModified;

    /** @var bool */
    protected $alldayEvent;

    /** @var bool */
    protected $public;

    /** @var string */
    protected $type;

    /** @var array */
    protected $options = [];

    /**
     * Initialize event
     *
     * @param int|string $id      Event identifier
     * @param array      $values  Event attribute values (name => value pairs)
     * @param array      $options Additional options passed to the event
     */
    public function __construct($id, array $values = [], array $options = [])
    {
        $this->id = $id;

        // Build unique event identifier
        $this->uid = ((isset($options['prefix_identifier']) && \is_string($options['prefix_identifier'])) ? $options['prefix_identifier'] : null) . $id;

        // Default values
        $this->start = $this->buildDateTime();
        $this->end = $this->buildDateTime();
        $this->created = $this->buildDateTime();
        $this->lastModified = $this->buildDateTime();
        $this->alldayEvent = self::DEFAULT_IS_ALLDAY_EVENT;
        $this->public = self::DEFAULT_IS_PUBLIC_EVENT;

        if (!empty($values)) {
            $this->hydrateValues($values);
        }

        $this->options = $options;
    }

    /**
     * Hydrate passed values into event attributes (if existing)
     * @param array $values
     * @return self
     */
    private function hydrateValues(array $values)
    {
        foreach ($values as $name => $value) {
            $this->$name = $value;
        }

        return $this;
    }

    /**
     * Magic method to access event attributes
     *
     * @param string $attribute Attribute name
     * @return mixed
     */
    public function __get($attribute)
    {
        if (\property_exists($this, $attribute)) {
            return $attribute;
        }

        return false;
    }

    /**
     * Magic method to modify event attribute
     *
     * @param string $attribute Attribut name
     * @param string $value     New value
     */
    public function __set($attribute, $value)
    {
        if (\property_exists($this, $attribute)) {
            $this->$attribute = $value;
        }
    }

    /**
     * Magic method to access event attributes by method call
     *
     * @param string $name attribut name
     * @param array  $args arguments
     * @return mixed
     */
    public function __call($name, $args = [])
    {
        if (0 === \strpos($name, 'get')) {
            // use getter method
            $property = \lcfirst(substr($name, 3));
            if (\property_exists($this, $property)) {
                return $this->{$property};
            }
        } else if (0 === \strpos($name, 'set')) {
            // use setter method
            $property = \lcfirst(substr($name, 3));
            if (\property_exists($this, $property)) {
                $this->{$property} = $args[0];
                return $this;
            }
        }

        // Ignoring method call
        return null;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Initialize date and time event attributes
     *
     * @return DateTime
     */
    private function buildDateTime($hour = null, $minute = null)
    {
        $dt = new DateTime();
        $dt->setTime(($hour ?: date('H')), ($minute ?: date('i')), 0);

        return $dt;
    }

    /**
     * Return unique event identifier
     *
     * @return int|string
     */
    public function getId()
    {
        return $this->uid;
    }

    /**
     * Return event identifier (only unique by source)
     * @return int|string
     */
    public function getUid()
    {
        return $this->id;
    }

    /**
     * Get event title (short title if empty)
     * @return string
     */
    public function getTitle()
    {
        return isset($this->title) ? $this->title : $this->getTitleShort();
    }

    /**
     *
     * @param string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;
        if (empty($this->titleShort)) {
            $this->titleShort = $title;
        }
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getTitleShort()
    {
        return $this->titleShort;
    }

    /**
     *
     * @param string $title
     * @return self
     */
    public function setTitleShort($title)
    {
        $this->titleShort = $title;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     *
     * @param string $description
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     *
     * @return DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     *
     * @return self
     */
    public function setStart(DateTime $dt)
    {
        $this->start = $dt;
        return $this;
    }

    /**
     *
     * @return DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     *
     * @return self
     */
    public function setEnd(DateTime $dt)
    {
        $this->end = $dt;
        return $this;
    }

    /**
     *
     * @return DateTime
     */
    public function getCreationDate()
    {
        return $this->created;
    }

    /**
     *
     * @param DateTime $dt
     * @return self
     */
    public function setCreationDate(DateTime $dt)
    {
        $this->created = $dt;
        return $this;
    }

    /**
     *
     * @return DateTime
     */
    public function getLastModificationDate()
    {
        return $this->lastModified;
    }

    /**
     *
     * @param DateTime $dt
     * @return self
     */
    public function setLastModificationDate(DateTime $dt)
    {
        $this->lastModified = $dt;
        return $this;
    }

    /**
     *
     * @return bool
     */
    public function isAlldayEvent() : bool
    {
        return $this->alldayEvent;
    }

    /**
     *
     * @param bool $allday
     * @return self
     */
    public function setAlldayEvent(bool $allday = self::DEFAULT_IS_ALLDAY_EVENT)
    {
        $this->alldayEvent = $allday;

        if ($allday === true) {
            $this->start->setTime(0, 0, 0);
            $this->end->setTime(23, 59, 59);
        }

        return $this;
    }

    /**
     *
     * @return bool
     */
    public function isPublic() : bool
    {
        return $this->public;
    }

    /**
     *
     * @param bool $status
     * @return self
     */
    public function setPublic(bool $status = self::DEFAULT_IS_PUBLIC_EVENT)
    {
        $this->public = $status;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     *
     * @param string $type
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
}
