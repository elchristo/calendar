<?php

namespace Elchristo\Calendar\Service\Builder;

use Elchristo\Calendar\Service\Config\Config;
use Elchristo\Calendar\Model\Event\CalendarEventInterface;
use Elchristo\Calendar\Model\Event\DefaultCalendarEvent;
use Elchristo\Calendar\Service\Color\DefaultColorStrategy;
use Elchristo\Calendar\Service\Color\ColorStrategyAwareInterface;

/**
 * Class to build instances of calendar events
 */
class EventBuilder
{
    private static $instance = null;
    private $configService;
    private $registeredEvents;
    private $registeredColors;
    private $registeredColorStrategies;

    /**
     * @param Config $config Calendar config
     */
    private function __construct(Config $config)
    {
        $this->configService = $config;
        $this->registeredEvents = $config->getRegisteredEvents();
        $this->registeredColors = $config->getRegisteredColors();
        $this->registeredColorStrategies = $config->getRegisteredColorStrategies();
    }

    /**
     * Build or return singleton instance of the builder
     *
     * @param Config $config
     * @return EventBuilder
     */
    public static function getInstance(Config $config)
    {
        if (self::$instance === null) {
            self::$instance = new self($config);
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

        if (\is_subclass_of($name, CalendarEventInterface::class) && \class_exists($name)) {
            $event = new $name($id, $values, $options);
        } else if (isset($this->registeredEvents[$name]) && true === \class_exists($this->registeredEvents[$name], \true)) {
            $event = new $this->registeredEvents[$name]($id, $values, $options);
        } else {
            $event = new DefaultCalendarEvent($id, $values, $options);
        }

        // Apply color strategy
        if ($event instanceof ColorStrategyAwareInterface) {
            if (isset($options['color_strategy'])) {
                $this->injectColorStrategy($event, $options['color_strategy']);
            } else {
                \trigger_error(\sprintf('Missing option "color_strategy" in event builder options for "%s". Default color strategy will be applied.', \get_class($event)), \E_USER_NOTICE);
            }
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
     * @param CalendarEventInterface $event
     * @param mixed[string|array]    $nameOrOptions
     * @return boolean
     */
    private function injectColorStrategy($event, $nameOrOptions)
    {
        $options = (\is_string($nameOrOptions))
            ? [ 'name' => $nameOrOptions ]
            : $nameOrOptions;

        if (!\is_array($options)) {
            \trigger_error(\sprintf('Passed color strategy options must be of type "array" for %s. Default color strategy will be applied.', \get_class($event)), \E_USER_NOTICE);
            $options = [ 'name' => null ];
        } else if (!isset($options['name']) || !\is_string($options['name'])) {
            \trigger_error(\sprintf('Missing color strategy option "name" in event builder options for "%s". Default color strategy will be applied.', \get_class($event)), \E_USER_NOTICE);
            $options['name'] = null;
        }

        $strategyName = $options['name'];
        $colorStrategy
            = (\array_key_exists($strategyName, $this->registeredColorStrategies)
                && \class_exists($this->registeredColorStrategies[$strategyName])
            )
                ? new $this->registeredColorStrategies[$strategyName]()
                : new DefaultColorStrategy();

        $colorStrategy
            ->setColorConfig($this->registeredColors)
            ->setEvent($event);

        if (isset($options['attributes']) && \is_array($options['attributes'])) {
            $colorStrategy->setAttributes($options['attributes']);
        }

        $event->setColorStrategy($colorStrategy);
    }
}
