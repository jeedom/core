<?php

namespace Jeedom\Core\Application\Configuration;

class ChainConfiguration implements Configuration
{
    /**
     * @var Configuration[]
     */
    private $configurations = [];

    public function __construct(array $configurations = [])
    {
        foreach ($configurations as $configuration) {
            $this->addPreConfiguration($configuration);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        if (empty($this->configurations)) {
            throw new \LogicException(self::class . ' doit être construit avec au moins une Configuration.');
        }

        foreach ($this->configurations as $configuration) {
            try {
                $value = $configuration->get($key);
            } catch (\Exception $e) {
                $value = null;
            }
            if (null !== $value) {
                return $value;
            }
        }

        return $default;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        if (empty($this->configurations)) {
            throw new \LogicException(self::class . ' doit être construit avec au moins une Configuration.');
        }

        $setted = false;
        foreach ($this->configurations as $configuration) {
            try {
                $configuration->set($key, $value);
                $setted = true;
            } catch (ReadOnlyConfigurationException $e) {
                if (!$setted) {
                    throw $e;
                }
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        if (empty($this->configurations)) {
            throw new \LogicException(self::class . ' doit être construit avec au moins une Configuration.');
        }

        $unsetted = false;
        foreach ($this->configurations as $configuration) {
            try {
                $configuration->remove($key);
                $unsetted = true;
            } catch (ReadOnlyConfigurationException $e) {
                if (!$unsetted) {
                    throw $e;
                }
            }
        }

        if (!$unsetted) {
            throw new ReadOnlyConfigurationException(
                self::class . ' est en lecture seule car il ne contient que des Configurations en lecture seule.'
            );
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function multiGet(array $keys, $default = null)
    {
        if (empty($this->configurations)) {
            throw new \LogicException(self::class . ' doit être construit avec au moins une Configuration.');
        }

        $asKeys = array_flip($keys);
        if (!is_array($default)) {
            $default = array_fill_keys($keys, $default);
        } else {
            $defaultValues = array_fill_keys($keys, null);
            $defaultLimitedToKeys = array_intersect_key($default, $asKeys);
            $default = array_merge($defaultValues, $defaultLimitedToKeys);
        }

        $returns = [];
        foreach ($this->configurations as $configuration) {
            $return = $configuration->multiGet($keys);
            $returns[] = $return;
            $notDefined = array_filter(
                $return,
                function($value) {
                    return null === $value;
                }
            );

            $keys = array_keys($notDefined);
            if (empty($notDefined)) {
                break;
            }
        }

        $return = array_merge(...$returns);
        $return = array_diff_key($return, array_flip($keys));

        return array_merge($default, $return);
    }

    /**
     * {@inheritdoc}
     */
    public function search($pattern)
    {
        if (empty($this->configurations)) {
            throw new \LogicException(self::class . ' doit être construit avec au moins une Configuration.');
        }

        $returns = [];
        foreach ($this->configurations as $configuration) {
            $returns[] = $configuration->search($pattern);
        }

        return array_merge([], ...array_reverse($returns));
    }

    public function addPreConfiguration(Configuration $configuration)
    {
        $this->configurations[] = $configuration;
    }

    public function addPostConfiguration(Configuration $configuration)
    {
        $this->configurations = array_merge([$configuration], $this->configurations);
    }
}
