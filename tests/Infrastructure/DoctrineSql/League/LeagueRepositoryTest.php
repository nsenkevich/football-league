<?php

namespace Championship\Tests\Infrastructure\DoctrineSql\League;

use Championship\Domain\League\League;
use Championship\Domain\League\TeamId;
use Championship\Infrastructure\DoctrineSql\League\LeagueRepository;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use PHPUnit\Framework\TestCase;

class LeagueRepositoryTest extends TestCase
{

    /**
     * @var LeagueRepository
     */
    private $leagueRepository;

    public function setUp()
    {
        parent::setUp();
        $paths = [__DIR__ . "/../../../../src/Infrastructure/DoctrineSql/League/mapping"];

        $dbParams = ['url' => 'sqlite:///:memory:'];
        if (!Type::hasType('LeagueId')) {
            Type::addType('LeagueId', 'Championship\Infrastructure\DoctrineSql\League\DoctrineLeagueId');
        }
        if (!Type::hasType('TeamId')) {
            Type::addType('TeamId', 'Championship\Infrastructure\DoctrineSql\League\DoctrineTeamId');
        }
        $config = Setup::createXMLMetadataConfiguration($paths, true);
        $entityManager = EntityManager::create($dbParams, $config);

        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
        $classes = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($classes);

        $this->leagueRepository = new LeagueRepository($entityManager);

    }

    public function testItShouldAddTeamToLeague()
    {
        $id = $this->leagueRepository->nextLeagueIdentity();
        $league = new League($id, 'first');
        $league->addTeam($league->createTeam(TeamId::create(uniqid()), 'red strike', 'red'));
        $this->leagueRepository->save($league);

        $this->assertEquals(1, $this->leagueRepository->leagueOfId($id)->teams()->count());
    }

    public function testItShouldAdd()
    {
        $id = $this->leagueRepository->nextLeagueIdentity();
        $this->leagueRepository->save(new League($id, 'first'));
        $this->assertNotNull($this->leagueRepository->leagueOfId($id));
    }

    public function testItShouldRemove()
    {
        $id = $this->leagueRepository->nextLeagueIdentity();
        $league = new League($id, 'first');
        $this->leagueRepository->save($league);
        $this->leagueRepository->remove($league);
        $this->assertNull($this->leagueRepository->leagueOfId($id));
    }

}
