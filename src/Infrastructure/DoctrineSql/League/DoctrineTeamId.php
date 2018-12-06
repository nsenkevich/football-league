<?php
declare(strict_types=1);

namespace Championship\Infrastructure\DoctrineSql\League;

use Championship\Domain\League\TeamId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class DoctrineTeamId extends GuidType
{
    public function getName(): string
    {
        return 'TeamId';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return (string)$value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): TeamId
    {
        return TeamId::create($value);
    }
}
