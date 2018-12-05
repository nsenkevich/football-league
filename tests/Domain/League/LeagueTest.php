<?php
declare(strict_types=1);

namespace Championship\Tests\Domain\League;

use Championship\Domain\League\League;
use Championship\Domain\League\LeagueId;
use Championship\Domain\League\TeamId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class LeagueTest extends TestCase
{

    public function testAddTeam()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Team already exist');

        $league = new League(LeagueId::create(uniqid()), 'new league');
        $teamId = TeamId::create(uniqid());
        $team = $league->createTeam($teamId, 'new team', 'red');

        $league->addTeam($team);
        $league->addTeam($team);
    }

    public function testSetNameEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Is empty');
        new League(LeagueId::create(uniqid()), '');
    }

    public function testSetNameInValidFormat()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid format');
        new League(LeagueId::create(uniqid()), '12121');
    }
}
