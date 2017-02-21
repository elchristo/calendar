<?php

namespace Elchristo\Calendar\Converter;

use Elchristo\Calendar\Exception\RuntimeException;
use Elchristo\Calendar\Model\CalendarInterface;

/**
 * Facade class to access converters and execute conversion process
 */
class Converter
{
    const DEFAULT_CONVERTER_NAMESPACE = __NAMESPACE__ . '\\';

    /** @var array Cached converter instances */
    private static $converters = [];

    /**
     * Convert events of given calendar into
     *
     * @param CalendarInterface $calendar Calendar to convert
     * @param string $name                Converter name / format
     * @param array  $options             Additional options
     *
     * @return ConverterInterface
     * @throws RuntimeException
     */
    public static function convert(CalendarInterface $calendar, $name, array $options = [])
    {
        if (isset(self::$converters[$name])) {
            $converter = self::$converters[$name];
        } else {
            // TODO allow configured namespaces
            $className = self::DEFAULT_CONVERTER_NAMESPACE . \ucfirst($name) . '\\' . ucfirst($name);

            if (true !== \class_exists($className, true)) {
                throw new RuntimeException(\sprintf('Unknown converter with name "%s" resolving to %s.', $name, $className));
            }

            $config = $calendar->getConfig();
            $eventBuilder = new ConvertibleEventFactory($config->getRegisteredConverters());
            $converter = new $className($eventBuilder);
        }

        return $converter->convert($calendar, $options);
    }
}
