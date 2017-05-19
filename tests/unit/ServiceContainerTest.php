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
        // given
        $config = self::getConfig();

        // when
        $firstInstance = ServiceContainer::create($config);
        $secondInstance = ServiceContainer::create();

        // then
        $this->assertInstanceOf(ServiceContainer::class, $firstInstance);
        $this->assertEquals($firstInstance, $secondInstance);
    }

    /**
     * Test if service container contains valid configuration
     */
    public function testHasValidConfig()
    {
        // given
        $config = $this->serviceContainer->getConfig();

        // then
        $this->assertArrayHasKey('elchristo', $config);
        $this->assertArrayHasKey('calendar', $config['elchristo']);
    }

    /**
     * Test if service container contains declarations for all mandatory factories
     */
    public function testContainsMandatoryFactories()
    {
        // given
        $serviceContainer = $this->serviceContainer;

        // then
        $this->assertTrue(
            $serviceContainer->has(CalendarBuilder::class),
            'Missing declared factory ' . CalendarBuilder::class
        );

        $this->assertTrue(
            $serviceContainer->has(SourceBuilder::class),
            'Missing declared factory ' . SourceBuilder::class
        );
    }

    /**
     * Test to retrieve calendar builder service from container
     */
    public function testGetCalendarBuilderFromContainer()
    {
        // given
        $serviceName = CalendarBuilder::class;

        // when
        $service = $this->serviceContainer->get($serviceName);

        // then
        $this->assertInstanceOf($serviceName, $service, 'Missing declared factory ' . $serviceName);
    }

    /**
     * Test to retrieve source builder service from container
     */
    public function testGetSourceBuilderFromContainer()
    {
        // given
        $serviceName = SourceBuilder::class;

        // when
        $service = $this->serviceContainer->get($serviceName);

        // then
        $this->assertInstanceOf($serviceName, $service, 'Missing declared factory ' . $serviceName);
    }
}
