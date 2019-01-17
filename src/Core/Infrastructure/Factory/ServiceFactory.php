<?php

namespace Jeedom\Core\Infrastructure\Factory;

use Jeedom\Core\Domain\Repository\CommandRepository;
use Jeedom\Core\Infrastructure\Service\ConfigurationColorConverter;
use Jeedom\Core\Infrastructure\Service\FilesystemWidgetService;
use Jeedom\Core\Infrastructure\Service\RepositoryHumanCommandMap;
use Jeedom\Core\Presenter\ColorConverter;
use Jeedom\Core\Presenter\HumanCommandMap;
use Jeedom\Core\Presenter\Service\WidgetService;

class ServiceFactory
{
    public static $map;

    public static $services = [];

    public static function build($serviceName)
    {
        if (isset(self::$services[$serviceName])) {
            return self::$services[$serviceName];
        }

        if (null === self::$map) {
            self::$map = 'test' === getenv('ENV') ? self::testMap() : self::prodMap();
        }

        if (!array_key_exists($serviceName, self::$map)) {
            throw new \LogicException('Aucune implémentation trouvée pour '.$serviceName);
        }

        $callable = self::$map[$serviceName];
        self::$services[$serviceName] = $callable();

        return self::$services[$serviceName];
    }

    public static function testMap()
    {
        $map = [

        ];

        return array_merge(self::prodMap(), $map);
    }

    public static function prodMap()
    {
        return [
            ColorConverter::class => function() { return new ConfigurationColorConverter(ConfigurationFactory::build('core')); },
            HumanCommandMap::class => function() { return new RepositoryHumanCommandMap(RepositoryFactory::build(CommandRepository::class)); },
            WidgetService::class => function() { return new FilesystemWidgetService(); }
        ];
    }
}
