<?php
declare(strict_types=1);

namespace Championship\Infrastructure\DoctrineSql;

use Championship\Infrastructure\DoctrineSql\League\DoctrineLeagueId;
use Championship\Infrastructure\DoctrineSql\League\DoctrineTeamId;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class EntityManagerFactory
{
    public static function create($paths, $isDevMode): EntityManager
    {
        $dbParams = ['driver' => 'pdo_sqlite', 'path' => __DIR__ . '../../../../var/db.sqlite'];
        Type::addType('LeagueId', DoctrineLeagueId::class);
        Type::addType('TeamId', DoctrineTeamId::class);

        $config = Setup::createXMLMetadataConfiguration([__DIR__ . $paths], $isDevMode);
        $entityManager =  EntityManager::create($dbParams, $config);

        return $entityManager;
    }
}
