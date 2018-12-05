<?php
declare(strict_types=1);

namespace Championship\Infrastructure\DoctrineSql\League;

use Championship\Domain\League\League;
use Championship\Domain\League\LeagueId;
use Championship\Domain\League\LeagueRepository as LeagueRepositoryInterface;
use Championship\Domain\League\Team;
use Championship\Domain\League\TeamId;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class LeagueRepository extends EntityRepository implements LeagueRepositoryInterface
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function nextLeagueIdentity(): LeagueId
    {
        return LeagueId::create(uniqid());
    }

    public function nextTeamIdentity(): TeamId
    {
        return TeamId::create(uniqid());
    }

    public function leagueOfId(LeagueId $anLeagueId): ?League
    {
        return $this->em->find(League::class, $anLeagueId);
    }

    public function teamOfId(TeamId $anTeamId): ?Team
    {
        return $this->em->find(Team::class, $anTeamId);
    }

    public function teamsOfLeagueId(LeagueId $anLeagueId): ?array
    {
        return $this->em->createQueryBuilder()
            ->select('p')
            ->from(Team::class, 'p')
            ->where('p.league= :id')
            ->setParameter('id', $anLeagueId)
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
    }

    public function save(League $anLeague): void
    {
        $this->em->persist($anLeague);
        $this->em->flush();
    }

    public function remove(League $anLeague): void
    {
        $this->em->remove($anLeague);
        $this->em->flush();
    }
}