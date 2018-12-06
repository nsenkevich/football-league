<?php
declare(strict_types=1);

namespace Championship\Domain\League;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;

class League
{
    const FORMAT = '/^[a-zA-Z ]+$/';

    private $id;
    private $name;
    private $teams;

    public function __construct(LeagueId $anId, string $name)
    {
        $this->id = $anId;
        $this->setName($name);
        $this->teams = new ArrayCollection();
    }

    private function setName(string $name)
    {
        $this->assertNotEmpty($name);
        $this->assertValidFormat($name);
        $this->name = $name;
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

    public function id(): LeagueId
    {
        return $this->id;
    }

    public function createTeam(TeamId $anTeamId, string $aName, string $aStrip): Team
    {
        return new Team($anTeamId, $aName, $aStrip, $this);
    }

    public function addTeam(Team $team): void
    {
        if ($this->teams->contains($team)) {
            throw new InvalidArgumentException('Team already exist');
        }

        $this->teams->add($team);
    }

    public function teams(): Collection
    {
        return $this->teams;
    }

    public function name(): string
    {
        return $this->name;
    }
}
