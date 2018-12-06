<?php
declare(strict_types=1);

namespace Championship\Domain\League;

interface LeagueRepository
{
    public function nextLeagueIdentity(): LeagueId;

    public function nextTeamIdentity(): TeamId;

    public function leagueOfId(LeagueId $anLeagueId): ?League;

    public function teamOfId(TeamId $anTeamId): ?Team;

    public function teamsOfLeagueId(LeagueId $anLeagueId): ?array;

    public function save(League $anLeague): void;

    public function remove(League $anLeague): void;
}
