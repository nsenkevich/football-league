<?php
declare(strict_types=1);

namespace Championship\Application\Service\League;

use Championship\Domain\League\League;
use Championship\Domain\League\LeagueId;
use Championship\Infrastructure\DoctrineSql\League\LeagueRepository;
use Championship\Domain\League\Team;
use Championship\Domain\League\TeamId;
use InvalidArgumentException;

class LeagueService
{

    private $leagueRepository;

    public function __construct(LeagueRepository $leagueRepository)
    {
        $this->leagueRepository = $leagueRepository;
    }

    public function createLeague(string $name): League
    {
        $league = new League($this->leagueRepository->nextLeagueIdentity(), $name);
        $this->leagueRepository->save($league);

        return $league;
    }

    public function deleteLeague(string $leagueId): bool
    {
        $league = $this->findLeagueOrFail(LeagueId::create($leagueId));
        $this->leagueRepository->remove($league);

        return true;
    }

    public function getTeams(string $leagueId): array
    {
        $this->findLeagueOrFail(LeagueId::create($leagueId));

        return $this->leagueRepository->teamsOfLeagueId(LeagueId::create($leagueId));
    }

    public function addTeam(string $leagueId, string $name, string $strip): Team
    {
        $league = $this->findLeagueOrFail(LeagueId::create($leagueId));
        $team = $league->createTeam($this->leagueRepository->nextTeamIdentity(), $name, $strip);
        $league->addTeam($team);
        $this->leagueRepository->save($league);

        return $team;
    }

    public function updateTeam(string $leagueId, string $teamId, string $name, string $strip): bool
    {
        $this->findLeagueOrFail(LeagueId::create($leagueId));
        $team = $this->findTeamOrFail(TeamId::create($teamId));
        $team->setStrip($strip);
        $team->setName($name);
        $this->leagueRepository->save($team->league());

        return true;
    }

    private function findLeagueOrFail(LeagueId $leagueId): ?League
    {
        $league = $this->leagueRepository->leagueOfId($leagueId);
        if (null === $league) {
            throw new InvalidArgumentException('League not found!');
        }

        return $league;
    }

    private function findTeamOrFail(TeamId $teamId): ?Team
    {
        $team = $this->leagueRepository->teamOfId($teamId);
        if (null === $team) {
            throw new InvalidArgumentException('Team not found!');
        }

        return $team;
    }

}