<?php

namespace Elchristo\Calendar\Test;

use Elchristo\Calendar\ServiceContainer;
use Elchristo\Calendar\Service\Builder\CalendarBuilder;
use Elchristo\Calendar\Service\Builder\SourceBuilder;

class ServiceContainerTest extends TestCase
{
    private $serviceContainer;

    protected function setUp()
    {
        parent::setUp();
        $this->serviceContainer = ServiceContainer::create(self::getConfig());
    }

    protected function tearDown()
    {
    }

    /**
     * Test if service container instance (singleton) can be created correctly
     */
    public function testCanCreateSingletonInstance()
    {
        $firstInstance = ServiceContainer::create(self::getConfig());
        $secondInstance = ServiceContainer::create();

        $this->assertInstanceOf(ServiceContainer::class, $firstInstance);
        $this->assertEquals($firstInstance, $secondInstance);
    }

    /**
     * Test if service container contains valid configuration
     */
    public function testHasValidConfig()
    {
        $config = $this->serviceContainer->getConfig();
        //$this->assertArrayHasKey('config', $config, 'Configuration needs root index "config"');
        $this->assertArrayHasKey('elchristo', $config);
        $this->assertArrayHasKey('calendar', $config['elchristo']);
    }

    /**
     * Test if service container contains declarations for all mandatory factories
     */
    public function testContainsMandatoryFactories()
    {
        $this->assertTrue(
            $this->serviceContainer->has(CalendarBuilder::class),
            'Missing declared factory ' . CalendarBuilder::class
        );

        $this->assertTrue(
            $this->serviceContainer->has(SourceBuilder::class),
            'Missing declared factory ' . SourceBuilder::class
        );
    }

    /**
     * Test to retrieve calendar builder service from container
     */
    public function testGetCalendarBuilderFromContainer()
    {
        $serviceName = CalendarBuilder::class;
        $this->assertInstanceOf($serviceName, $this->serviceContainer->get($serviceName), 'Missing declared factory ' . $serviceName);
    }

    /**
     * Test to retrieve source builder service from container
     */
    public function testGetSourceBuilderFromContainer()
    {
        $serviceName = SourceBuilder::class;
        $this->assertInstanceOf($serviceName, $this->serviceContainer->get($serviceName), 'Missing declared factory ' . $serviceName);
    }
}
