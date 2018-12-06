<?php

namespace Championship\Tests\Application\Service\League;

use Championship\Application\Service\League\LeagueService;
use Championship\Domain\League\League;
use Championship\Domain\League\LeagueId;
use Championship\Domain\League\TeamId;
use Championship\Infrastructure\DoctrineSql\League\LeagueRepository;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LeagueServiceTest extends TestCase
{

    /**
     * @var LeagueService
     */
    private $leagueService;

    /**
     * @var MockObject
     */
    private $leagueRepository;

    public function setUp()
    {
        $this->leagueRepository = $this->createMock(LeagueRepository::class);
        $this->leagueService = new LeagueService($this->leagueRepository);
    }

    public function testCreateLeague()
    {
        $leagueId = LeagueId::create('5c0840eea092a');
        $league = new League($leagueId, 'first');

        $this->leagueRepository->method('nextLeagueIdentity')->willReturn($leagueId);
        $this->leagueRepository->expects($this->once())->method('save')->with($league);

        $this->assertEquals($league, $this->leagueService->createLeague('first'));
    }

    public function testUpdateTeam()
    {
        $leagueId = LeagueId::create('5c0840eea092a');
        $league = new League($leagueId, 'Premier');
        $teamId = TeamId::create('5c0841c053a87');
        $team = $league->createTeam($teamId, 'manchester united', 'red');

        $this->leagueRepository->method('teamOfId')->with($teamId)->willReturn($team);
        $this->leagueRepository->method('leagueOfId')->with($leagueId)->willReturn($league);

        $team->setName('chelsea');
        $team->setStrip('blue');
        $league->addTeam($team);

        $this->leagueRepository->expects($this->once())->method('save')->with($league);

        $this->assertEquals(true, $this->leagueService->updateTeam('5c0840eea092a', '5c0841c053a87', 'chelsea', 'blue'));
    }

    public function testAddTeam()
    {
        $leagueId = LeagueId::create('5c0840eea092a');
        $league = new League($leagueId, 'Premier');
        $teamId = TeamId::create('teamId');
        $league->addTeam($league->createTeam($teamId, 'manchester united', 'red'));

        $this->leagueRepository->method('leagueOfId')->with($leagueId)->willReturn($league);
        $this->leagueRepository->expects($this->once())->method('save')->with($league);

        $this->assertEquals('manchester united', $this->leagueService->addTeam('5c0840eea092a', 'manchester united', 'red')->name());
    }

    public function testDeleteLeague()
    {
        $leagueId = LeagueId::create('5c0840eea092a');
        $league = new League($leagueId, 'Premier');

        $this->leagueRepository->method('leagueOfId')->with($leagueId)->willReturn($league);
        $this->leagueRepository->expects($this->once())->method('remove')->with($league);

        $this->assertEquals(true, $this->leagueService->deleteLeague('5c0840eea092a'));
    }

    public function testGetTeams()
    {
        $leagueId = LeagueId::create('5c0840eea092a');
        $league = new League($leagueId, 'Premier');
        $teamId = TeamId::create('5c0841c053a87');
        $team = $league->createTeam($teamId, 'manchester united', 'red');
        $league->addTeam($team);

        $this->leagueRepository->method('leagueOfId')->with($leagueId)->willReturn($league);
        $this->leagueRepository->method('teamsOfLeagueId')->with($leagueId)->willReturn([$team]);
        $this->assertEquals([$team], $this->leagueService->getTeams('5c0840eea092a'));
    }

    public function testGetTeamsForNotExistingLeague()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('League not found!');
        $leagueId = LeagueId::create('5c0840eea092a');
        $this->leagueRepository->method('leagueOfId')->with($leagueId)->willReturn(null);
        $this->leagueService->getTeams('5c0840eea092a');
    }

    public function testUpdateTeamForNotExistingTeam()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Team not found!');
        $teamId = TeamId::create('5c0841c053a87');

        $leagueId = LeagueId::create('5c0840eea092a');
        $league = new League($leagueId, 'Premier');

        $this->leagueRepository->method('leagueOfId')->with($leagueId)->willReturn($league);

        $this->leagueRepository->method('teamOfId')->with($teamId)->willReturn(null);
        $this->leagueService->updateTeam('5c0840eea092a', '5c0841c053a87', 'chelsea', 'blue');
    }
}
