<?php

namespace Jeedom\Core\Infrastructure\Factory;

use Jeedom\Core\Domain\Repository\CommandRepository;
use Jeedom\Core\Infrastructure\Repository\SQLDatabaseCommandRepository;

class RepositoryFactory
{
    public static $map;

    public static $repositories = [];

    public static function build($repositoryName)
    {
        if (isset(self::$repositories[$repositoryName])) {
            return self::$repositories[$repositoryName];
        }

        if (null === self::$map) {
            self::$map = 'test' === getenv('ENV') ? self::testMap() : self::prodMap();
        }

        if (!array_key_exists($repositoryName, self::$map)) {
            throw new \LogicException('Aucune implémentation trouvée pour '.$repositoryName);
        }

        $callable = self::$map[$repositoryName];
        self::$repositories[$repositoryName] = $callable();

        return self::$repositories[$repositoryName];
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
            CommandRepository::class => function() { return new SQLDatabaseCommandRepository(); },
        ];
    }
}
