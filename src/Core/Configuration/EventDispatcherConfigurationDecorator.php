<?php

namespace Jeedom\Core\Configuration;

use Jeedom\Core\Configuration\Event\Configured;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventDispatcherConfigurationDecorator implements Configuration
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var Configuration
     */
    private $decoratedConfiguration;

    private $plugin;

    public function __construct($plugin, EventDispatcherInterface $eventDispatcher, Configuration $configuration)
    {
        $this->plugin = $plugin;
        $this->eventDispatcher = $eventDispatcher;
        $this->decoratedConfiguration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        return $this->decoratedConfiguration->get($key, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        $event = $this->eventDispatcher->dispatch('preConfig', new Configured($key, $value, $this->plugin));
        $value = $event->getValue();
        $this->decoratedConfiguration->set($key, $value);
        $this->eventDispatcher->dispatch('postConfig', new Configured($key, $value, $this->plugin));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        $this->decoratedConfiguration->remove($key);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function multiGet(array $keys, $default = null)
    {
        return $this->decoratedConfiguration->multiGet($keys, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function search($pattern)
    {
        return $this->decoratedConfiguration->search($pattern);
    }
}
