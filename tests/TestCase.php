<?php

namespace Elchristo\Calendar\Test;

use Zend\ServiceManager\ServiceManager;
use Elchristo\Calendar\Service\Builder\CalendarBuilder;

/**
 * Base class for all unit tests
 */
abstract class TestCase extends \Codeception\Test\Unit
{
    /** @var \UnitTester */
    protected $tester;

    public static $config = null;

    public static $serviceContainer = null;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * Returns test configuration
     * @return array
     */
    public static function getConfig()
    {
        if (self::$config === null) {
            self::$config = \array_merge_recursive(
                require(__DIR__ . '/_data/services.config.php'),
                require(__DIR__ . '/_data/config.php')
            );
        }

        return self::$config;
    }

    /**
     *
     * @return \Interop\Container\ContainerInterface
     */
    public static function getServiceContainer()
    {
        if (self::$serviceContainer === null) {
            self::$serviceContainer = new ServiceManager(self::getConfig());
        }

        return self::$serviceContainer;
    }

    /**
     * Return calendar builder instance
     *
     * @param array $config
     * @return CalendarBuilder
     */
    protected function getCalendarBuilder(array $config = [])
    {
        if (empty($config)) {
            $config = $this->getConfig();
        }

        return self::getServiceContainer()->build(CalendarBuilder::class, $config);
    }
}
