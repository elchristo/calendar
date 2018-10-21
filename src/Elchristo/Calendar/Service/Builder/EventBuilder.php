<?php

namespace Elchristo\Calendar\Service\Builder;

use Elchristo\Calendar\Model\Event\CalendarEventInterface;
use Elchristo\Calendar\Model\Event\DefaultCalendarEvent;
use Elchristo\Calendar\Service\Color\DefaultColorStrategy;
use Elchristo\Calendar\Model\Event\ColoredEventInterface;
use Interop\Container\ContainerInterface;
use Elchristo\Calendar\Service\SourceLocator;

/**
 * Class to build instances of calendar events
 */
class EventBuilder
{
    private static $instance = null;
    private $sourceLocator;
    private $eventColors = [];

    /**
     * @param SourceLocator $sourceLocator
     * @param array         $options
     */
    private function __construct(SourceLocator $sourceLocator, array $options = [])
    {
        $this->sourceLocator = $sourceLocator;
        $this->eventColors = isset($options['event_colors']) && \is_array($options['event_colors']) ? $options['event_colors'] : [];
    }

    /**
     * Build or return singleton instance of the builder
     *
     * @param SourceLocator $sourceLocator
     * @param array         $options
     * @return EventBuilder
     */
    public static function getInstance(SourceLocator $sourceLocator, array $options = [])
    {
        if (self::$instance === null) {
            self::$instance = new self($sourceLocator, $options);
        }

        return self::$instance;
    }

    /**
     * Build a new calendar event instance.
     * If no class found by passed name (in configuration) an instance of DefaultCalendarEvent is returned as a fallback
     *
     * @param string $name    Event name (in configuration or classname)
     * @param array  $values  Event attribute values (name => value pairs)
     * @param array  $options Additional options passed to the event
     *
     * @return CalendarEventInterface
     */
    public function build($name, array $values = [], array $options = [])
    {
        if (isset($values['id'])) {
            $id = $values['id'];
            unset($values['id']);
        } else {
            $id = $this->generateEventId($name);
        }

        $event = (\is_subclass_of($name, CalendarEventInterface::class) && \class_exists($name))
            ? new $name($id, $values, $options)
            : new DefaultCalendarEvent($id, $values, $options);

        // Apply color strategy
        if ($event instanceof ColoredEventInterface) {
            $colorStrategyOptions = (isset($options['color_strategy'])) ? $options['color_strategy'] : \null;
            $this->initColorStrategy($event, $colorStrategyOptions);
        }

        return $event;
    }

    /**
     * Generates an unique random event identifier prefixed by event name
     *
     * @param string $name The event name
     * @return string
     */
    private function generateEventId($name)
    {
        return \uniqid(\strtolower($name), \false);
    }

    /**
     * Injects color strategy (if declared in configuration by name), otherwise the default color strategy
     *
     * @param ColoredEventInterface   $event
     * @param string|array|null $nameOrOptions
     * @return boolean
     */
    private function initColorStrategy($event, $nameOrOptions)
    {
        $options = (\is_string($nameOrOptions))
            ? [ 'name' => $nameOrOptions ]
            : $nameOrOptions;

        if (!\is_array($options)) {
            // Invalid or empty color strategy parameter passed, default color strategy will be initialized
            $options = [ 'name' => null ];
        } else if (!isset($options['name']) || !\is_string($options['name'])) {
            \trigger_error(\sprintf('Missing color strategy option "name" in event builder options for "%s". Default color strategy will be applied.', \get_class($event)), \E_USER_NOTICE);
            $options['name'] = null;
        }

        $strategyName = $options['name'];
        $colorStrategy
            = ($this->sourceLocator->getContainer()->has($strategyName))
                ? $this->sourceLocator->getContainer()->get($strategyName)
                : $this->sourceLocator->getContainer()->get(DefaultColorStrategy::class);

        $colorStrategy
            ->setColorConfig($this->eventColors)
            ->setEvent($event);

        if (isset($options['attributes']) && \is_array($options['attributes'])) {
            $colorStrategy->setAttributes($options['attributes']);
        }

        $event->setColorStrategy($colorStrategy);
    }
}
