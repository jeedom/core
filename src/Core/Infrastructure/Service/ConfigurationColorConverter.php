<?php

namespace Jeedom\Core\Infrastructure\Service;

use Jeedom\Core\Configuration\Configuration;

class ConfigurationColorConverter
{
    private $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function convert($color)
    {
        $colors = $this->configuration->get('convertColor');
        if (isset($colors[$color])) {
            return $colors[$color];
        }
        throw new \Exception(__('Impossible de traduire la couleur en code hexadécimal :', __FILE__) . $color);
    }
}
