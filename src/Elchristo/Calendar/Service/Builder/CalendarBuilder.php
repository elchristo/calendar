<?php

namespace Elchristo\Calendar\Service\Builder;

use Elchristo\Calendar\Exception\InvalidArgumentException;
use Elchristo\Calendar\Model\CalendarInterface;
use Elchristo\Calendar\Model\Event\Collection as EventsCollection;
use Elchristo\Calendar\Service\Config\ConfigAwareInterface;
use Elchristo\Calendar\Service\Config\ConfigProviderTrait;
use Elchristo\Calendar\Service\Builder\SourceBuilder;
use Elchristo\Calendar\Model\DefaultCalendar;

/**
 * Class to build calendar instances
 */
class CalendarBuilder implements ConfigAwareInterface
{
    use ConfigProviderTrait;

    /** @var SourceBuilder */
    protected $sourceBuilder;

    /**
     * Build a new calendar instance by name declared in configuration
     *
     * @param string $name The declared calendar class
     * @param array  $sources Associative array of calendar sources (eg. ['sourceA' => [options...]])
     *
     * @return CalendarInterface
     */
    public function build(string $name, array $sources = [])
    {
        if (empty($name)) {
            throw new InvalidArgumentException("Calendar name must not be empty.");
        }

        $config = $this->getConfig();
        $sourceBuilder = $this->getSourceBuilder();
        $sourceBuilder->setConfig($config);
        $eventsCollection = new EventsCollection();

        if (\is_subclass_of($name, CalendarInterface::class)) {
            $calendar = new $name($sourceBuilder, $eventsCollection);
        } else if (!\class_exists($name)) {
            $calendar = new DefaultCalendar($sourceBuilder, $eventsCollection);
        } else {
            throw new InvalidArgumentException(\sprintf('Cannot create calendar by passed name "%s". Either a class implementing CalendarInterface or a string (not a class name) accepted.', $name));
        }

        $calendar
            ->setConfig($config)
            ->setName($name)
            ->init();

        if (!empty($sources)) {
            foreach ($sources as $sourceName => $options) {
                $calendar->addSource($sourceName, $options);
            }
        }

        return $calendar;
    }

    /**
     * Get injected source builder instance
     *
     * @return SourceBuilder
     */
    public function getSourceBuilder()
    {
        return $this->sourceBuilder;
    }

    /**
     * Inject source builder
     *
     * @param SourceBuilder $sourceBuilder
     * @return CalendarBuilder
     */
    public function setSourceBuilder(SourceBuilder $sourceBuilder)
    {
        $this->sourceBuilder = $sourceBuilder;
        return $this;
    }
}
