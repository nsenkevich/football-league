<?php
declare(strict_types=1);

namespace Championship\Tests\Domain\League;

use Championship\Domain\League\League;
use Championship\Domain\League\LeagueId;
use Championship\Domain\League\Team;
use Championship\Domain\League\TeamId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TeamTest extends TestCase
{

    public function testSetNameEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Is empty');

        $league = new League(LeagueId::create(uniqid()), 'new league');
        new Team(TeamId::create(uniqid()), '', 'red', $league);
    }

    public function testSetNameInValidFormat()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid format');

        $league = new League(LeagueId::create(uniqid()), 'new league');
        new Team(TeamId::create(uniqid()), '123456', 'red', $league);
    }

    public function testSetStripEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Is empty');

        $league = new League(LeagueId::create(uniqid()), 'new league');
        new Team(TeamId::create(uniqid()), 'new team', '', $league);
    }

    public function testSetStripInValidFormat()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid format');

        $league = new League(LeagueId::create(uniqid()), 'new league');
        new Team(TeamId::create(uniqid()), 'new team', '12344', $league);
    }
}
