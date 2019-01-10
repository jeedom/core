<?php

namespace Jeedom\Core\Configuration;

class InMemoryConfiguration implements Configuration
{
    private $configuration;

    public function __construct(array $configuration = [])
    {
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        if (!isset($this->configuration[$key])) {
            return $default;
        }

        return $this->configuration[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        $this->configuration[$key] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        unset($this->configuration[$key]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function multiGet(array $keys, $default = null)
    {
        $asKeys = array_flip($keys);
        if (!is_array($default)) {
            $default = array_fill_keys($keys, $default);
        } else {
            $defaultValues = array_fill_keys($keys, null);
            $defaultLimitedToKeys = array_intersect_key($default, $asKeys);
            $default = array_merge($defaultValues, $defaultLimitedToKeys);
        }

        $return = array_intersect_key($this->configuration, $asKeys);

        return array_merge($default, $return);
    }

    /**
     * {@inheritdoc}
     */
    public function search($pattern)
    {
        return array_filter($this->configuration, function($key) use ($pattern) {
            return false !== stripos($key, $pattern);
        }, ARRAY_FILTER_USE_KEY);
    }
}
