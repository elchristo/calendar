<?php

namespace Elchristo\Calendar\Converter\FullCalendar\Event;

use \DateTime;
use Elchristo\Calendar\Converter\ConvertibleEventInterface;
use Elchristo\Calendar\Model\Event\CalendarEventInterface;
use Elchristo\Calendar\Model\Event\Collection;

/**
 * Abstract FullCalendar JSON event
 */
abstract class AbstractFullCalendarEvent implements ConvertibleEventInterface
{
    /** @var bool */
    const ATTR_EDITABLE = false;

    /** @var string iso8601 datetime format without timezone */
    const FC_DATETIME_FORMAT = 'Y-m-d\TH:i:s';

    /** @var CalendarEventInterface original event */
    protected $event;

    /** @var string event title */
    protected $title;

    /** @var string title details to show on hover */
    protected $titleDetails = null;

    /** @var string event description */
    protected $description;

    /** @var array attributes */
    protected $attributes = [];

    /** @var array */
    protected $options = [];

    /**
     * @param CalendarEventInterface $e Calendar event to convert
     */
    public function __construct(CalendarEventInterface $e)
    {
        $this->event = $e;
        $this->title = $e->getTitle();
        $this->description = !empty($e->getDescription()) ? $e->getDescription() : '';
    }

    /**
     * Build FullCalendar event as array
     *
     * Keys : id, title, description, allDay, start, end, color, editable
     *
     * @return array
     */
    public function asArray()
    {
        $aEvent = [
            'id'          => $this->event->getUid(),
            'idBySource'  => $this->event->getId(),
            'title'       => $this->title,
            'titleDetails' => \is_null($this->titleDetails) ? $this->description : $this->titleDetails,
            'description' => $this->description,
            'published'   => $this->event->isPublic(),
            'type'        => $this->event->getType(),
            'allDay'      => $this->event->isAlldayEvent(),
            'start'       => $this->convertDatetimeForFullCalendar($this->event->getStart()),
            'end'         => $this->convertDatetimeForFullCalendar($this->event->getEnd()),
            //'color'       => $this->event->getColorCode(),
            'editable'    => self::ATTR_EDITABLE,
        ];

        return (!empty($this->attributes)) ? $this->mergeAttributes($aEvent) : $aEvent;
    }

    /**
     * Return converted FullCalendar event as JSON
     *
     * @return string
     */
    public function asJson()
    {
        return \str_replace("\/", '/', \json_encode($this->asArray()));
    }

    /**
     * Transform datetime into ISO8601 (required by FullCalendar)
     *
     * @param DateTime $dt
     * @return string
     */
    private function convertDatetimeForFullCalendar(DateTime $dt)
    {
        return \date(self::FC_DATETIME_FORMAT, $dt->getTimestamp());
    }

    /**
     * __call
     *
     * @param string $name      method name
     * @param array  $arguments arguments
     *
     * @return self
     */
    public function __call($name, $arguments)
    {
        if (!\function_exists($this->$name)) {
            return null;
        }
    }

    /**
     * Set additional attributes
     *
     * @param array $attributes
     * @return self
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Merge default attributes with event attributes
     *
     * @param array $event FullCalendar event
     * @return array
     */
    protected function mergeAttributes($event = [])
    {
        if (!\is_array($event)) {
            return [];
        } elseif (!\is_array($this->attributes)) {
            return $event;
        }

        foreach ($this->attributes as $key => $value) {
            $event[$key] = $this->findAttributeValue($value);
        }

        return $event;
    }

    /**
     * Find attribut by value
     *
     * @param mixed array|string|object $attributes single or multiple attributes
     * @param string                    $value      value
     * @return string
     */
    private function findAttributeValue($attributes, $value = null)
    {
        // Construire tableau avec les éléments
        $aElements = (\is_array($attributes)) ? $attributes : [$attributes];

        // Parcourir les éléments
        foreach ($aElements as $elem) {
            if (\is_array($elem)) {
                $value .= $this->findAttributeValue($elem, $value);
            } elseif (\is_string($elem)) {
                // check if value is a (getter) method name
                $method = (\ctype_alnum($elem))
                    ? (\method_exists($this->event, $elem)
                        ? $elem
                        : 'get' . \ucfirst($elem)
                    ) : false;

                if (false !== $method && ($attr = $this->event->$method())) {
                    // replace value by attribute
                    if (\is_object($attr)) {
                        $value .= $this->findAttributeValue($attr, $value);
                    } else {
                        $value .= \utf8_encode($attr);
                    }
                } else {
                    // value is a string
                    $value .= \utf8_encode($elem);
                }
            } elseif (\is_bool($elem) || \is_int($elem)) {
                $value = $elem;
            } elseif (\is_object($elem)) {
                if (\in_array(Collection::class, \class_implements($elem))) {
                    $value = $elem->toList(['title']);
                }
            }
        }

        return $value;
    }

    /**
     * Setting event options
     *
     * @param array $options
     * @return self
     *
     */
    public function setOptions(array $options = [])
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Return event options
     *
     * @return array $options
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Return option if existing
     *
     * @param string $key option identifier
     * @return mixed either option value or FALSE if not found by passed identifier
     */
    public function getOption($key)
    {
        return (\is_string($key) && !empty($key) && \array_key_exists($key, $this->options))
            ? $this->options[$key]
            : false;
    }
}