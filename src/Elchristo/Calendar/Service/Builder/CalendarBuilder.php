<?php

namespace Elchristo\Calendar\Service\Builder;

use Elchristo\Calendar\Exception\InvalidArgumentException;
use Elchristo\Calendar\Model\AbstractCalendar;
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
     * @param string $name The declared calendar name
     * @param array  $sources Associative array of calendar sources (eg. ['sourceA' => [options...]])
     *
     * @return AbstractCalendar
     */
    public function build($name, array $sources = [])
    {
        if (empty($name)) {
            throw new InvalidArgumentException("Calendar name must not be empty.");
        }

        $config = $this->getConfig();
        $registeredCalendars = $config->getRegisteredCalendars();
        $sourceBuilder = $this->getSourceBuilder();
        $eventsCollection = new EventsCollection();

        if (!\array_key_exists($name, $registeredCalendars) || !\class_exists($registeredCalendars[$name])) {
            $calendar = new DefaultCalendar($sourceBuilder, $eventsCollection);
        } else {
            $className = $registeredCalendars[$name];
            $calendar = new $className($sourceBuilder, $eventsCollection);
        }

        // TODO : This should always be the case
        if ($calendar instanceof ConfigAwareInterface) {
            $calendar->setConfig($config);
        }

        $calendar
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
