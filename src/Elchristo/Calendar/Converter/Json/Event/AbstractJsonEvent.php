<?php

namespace Elchristo\Calendar\Converter\Json\Event;

use Elchristo\Calendar\Model\Event\CalendarEventInterface;
use Elchristo\Calendar\Converter\ConvertibleEventInterface;

/**
 * Abstract Json calendar event
 */
abstract class AbstractJsonEvent implements ConvertibleEventInterface
{
    /** @var CalendarEventInterface Calendar event to be converted */
    protected $event;

    /** @var string Additional converter options */
    protected $options = [];

    /**
     * @param CalendarEventInterface $event Calendar event to be converted
     */
    public function __construct(CalendarEventInterface $event)
    {
        $this->event = $event;
    }

    /**
     * Set additional options
     *
     * @param array $options
     * @return AbstractVEvent
     *
     */
    public function setOptions(array $options = [])
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Get additional converter options
     *
     * @return array $options
     *
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Get single converter option by name
     *
     * @param string $name
     * @return mixed $option Option value if found, otherwise NULL
     *
     */
    public function getOption($name)
    {
        return (\strlen($name) > 1 && \array_key_exists($name, $this->options))
            ? $this->options[$name]
            : null;
    }

    /**
     * Return event values as array
     * @return array
     */
    public function asArray()
    {
        $event = [
            'id' => $this->event->getId(),
            'uid' => $this->event->getUid(),
            'title' => $this->event->getTitle(),
            'title_short' => $this->event->getTitleShort(),
            'description' => $this->event->getDescription(),
            'type' => $this->event->getType(),
            'all_day_event' => $this->event->isAlldayEvent(),
            'start' => ($this->event->getStart()) ? $this->event->getStart()->getTimestamp() : null,
            'end' => ($this->event->getEnd()) ? $this->event->getEnd()->getTimestamp() : null,
            'created' => $this->event->getCreationDate()->getTimestamp(),
            'last_modified' => $this->event->getLastModificationDate()->getTimestamp(),
        ];

        if ($this->event instanceof \Elchristo\Calendar\Model\Event\ColoredEventInterface) {
            $event['color'] = $this->event->getColorCode();
        }

        return $event;
    }

    /**
     * Return converted event as JSON
     * @return string
     */
    public function asJson()
    {
        return \str_replace("\/", '/', \json_encode([ $this->event->getId() => $this->asArray() ]));
    }
}
