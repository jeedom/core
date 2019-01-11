<?php

namespace Jeedom\Core\Configuration\Event;

use Symfony\Component\EventDispatcher\Event;

class Configured extends Event
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var string
     */
    private $plugin;

    /**
     * @param string $key
     * @param mixed $value
     * @param string $plugin
     */
    public function __construct($key, $value, $plugin)
    {
        $this->key = $key;
        $this->value = $value;
        $this->plugin = $plugin;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return Configured
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlugin()
    {
        return $this->plugin;
    }
}
