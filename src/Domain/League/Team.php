<?php
declare(strict_types=1);

namespace Championship\Domain\League;

use InvalidArgumentException;

class Team
{
    const FORMAT = '/^[a-zA-Z ]+$/';

    private $id;
    private $name;
    private $strip;
    private $league;

    public function __construct(TeamId $anId, string $aName, string $aStrip, League $aLeague)
    {
        $this->id = $anId;
        $this->setName($aName);
        $this->setStrip($aStrip);
        $this->league = $aLeague;
    }

    public function setName(string $aName)
    {
        $this->assertNotEmpty($aName);
        $this->assertValidFormat($aName);
        $this->name = $aName;
    }

    private function assertNotEmpty(string $value)
    {
        if (empty($value)) {
            throw new InvalidArgumentException('Is empty');
        }
    }

    private function assertValidFormat(string $value)
    {
        if (preg_match(self::FORMAT, $value) !== 1) {
            throw new InvalidArgumentException('Invalid format');
        }
    }

    public function setStrip(string $strip)
    {
        $this->assertNotEmpty($strip);
        $this->assertValidFormat($strip);
        $this->strip = $strip;
    }

    public function id(): TeamId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function strip(): string
    {
        return $this->strip;
    }

    public function league(): League
    {
        return $this->league;
    }

}