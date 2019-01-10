<?php

namespace Tests\Jeedom\Core\Configuration;

use Jeedom\Core\Configuration\Configuration;
use Jeedom\Core\Configuration\Event\Configured;
use Jeedom\Core\Configuration\EventDispatcherConfigurationDecorator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

class EventDispatcherConfigurationDecoratorTest extends TestCase
{
    public function testPreConfigIsDispatched()
    {
        $configuration = $this->createMock(Configuration::class);
        $configuration->expects($this->once())->method('set');

        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addListener('preConfig', function(Configured $event) { echo $event->getKey() .' => '. $event->getValue(); });
        $eventDispatcherConfiguration = new EventDispatcherConfigurationDecorator('core', $eventDispatcher, $configuration);

        $this->expectOutputString('foo => bar');
        $eventDispatcherConfiguration->set('foo', 'bar');
    }

    public function testPostConfigIsDispatched()
    {
        $configuration = $this->createMock(Configuration::class);
        $configuration->expects($this->once())->method('set');

        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addListener('postConfig', function(Configured $event) { echo $event->getKey() .' => '. $event->getValue(); });
        $eventDispatcherConfiguration = new EventDispatcherConfigurationDecorator('core', $eventDispatcher, $configuration);

        $this->expectOutputString('foo => bar');
        $eventDispatcherConfiguration->set('foo', 'bar');
    }
}
