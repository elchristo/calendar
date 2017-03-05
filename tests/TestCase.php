<?php

namespace Elchristo\Calendar\Test;

/**
 * Base class for all unit tests
 */
abstract class TestCase extends \Codeception\Test\Unit
{
    /** @var \UnitTester */
    protected $tester;

    public static $config = null;

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
            self::$config = require(__DIR__ . '/_data/config.php');
        }

        return self::$config;
    }
}
