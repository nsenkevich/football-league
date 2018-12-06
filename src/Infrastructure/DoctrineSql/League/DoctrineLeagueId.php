<?php
declare(strict_types=1);

namespace Championship\Infrastructure\DoctrineSql\League;

use Championship\Domain\League\LeagueId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class DoctrineLeagueId extends GuidType
{
    public function getName(): string
    {
        return 'LeagueId';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return (string)$value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): LeagueId
    {
        return LeagueId::create($value);
    }
}
