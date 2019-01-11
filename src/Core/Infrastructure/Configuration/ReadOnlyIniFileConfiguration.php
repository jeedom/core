<?php

namespace Jeedom\Core\Configuration;

class ReadOnlyIniFileConfiguration extends InMemoryConfiguration
{
    /**
     * {@inheritdoc}
     */
    public function __construct($filename, $section)
    {
        if (!file_exists($filename)) {
            throw new \Exception('Le fichier ' . $filename . ' est introuvable.');
        } elseif (!is_readable($filename)) {
            throw new \Exception('Le fichier ' . $filename . ' est inaccessible en lecture.');
        }
        $configuration = parse_ini_file($filename, true);
        if (isset($configuration[$section])) {
            parent::__construct($configuration[$section]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        throw new ReadOnlyConfigurationException('Cette configuration est en lecture seule.');
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        throw new ReadOnlyConfigurationException('Cette configuration est en lecture seule.');
    }
}
