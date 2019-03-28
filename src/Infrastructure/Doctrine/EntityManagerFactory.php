<?php
declare(strict_types=1);

namespace NAC\Infrastructure\Doctrine;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class EntityManagerFactory
{
    public static function create(string $driver, string $pathToDb) : EntityManager
    {
        $config = static::getConfiguration();
        $dbParams = array(
            'driver' => $driver,
            'path' => $pathToDb,
        );

        return EntityManager::create($dbParams, $config);
    }

    protected static function getConfiguration() : Configuration
    {
        $pathsDir = __DIR__ . "/../../../";
        $paths = [$pathsDir . "database/mappings"];
        $config = Setup::createXMLMetadataConfiguration($paths);
        return $config;
    }
}