<?php

namespace Championship\Tests\Application\Controller;

use Championship\Application\Resource\LeagueResource;
use Championship\Application\Service\League\LeagueService;
use Championship\Domain\League\League;
use Championship\Domain\League\LeagueId;
use Championship\Domain\League\TeamId;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LeagueResourceTest extends TestCase
{

    /**
     * @var MockObject
     */
    private $leagueService;

    /**
     * @var LeagueResource
     */
    private $resorce;

    public function setUp()
    {
        $this->leagueService = $this->createMock(LeagueService::class);
        $this->resorce = new LeagueResource($this->leagueService);
    }

    public function testFetchTeams()
    {
        $data = [['name' => 'chelsea', 'strip' => 'blue']];
        $expected = new JsonResponse($data, 200);
        $this->leagueService->method('getTeams')->willReturn($data);

        $this->assertEquals($expected, $this->resorce->fetchTeams('5c0841c053a87'));
    }

    public function testUpdateTeam()
    {
        $expected = new JsonResponse(['success' => true], 200);
        $request = new Request([],[],[],[],[],[],json_encode(['name' => 'chelsea', 'strip' => 'blue']));

        $this->leagueService->method('updateTeam')->with('5c0841aeefd50', '5c0841c053a87', 'chelsea', 'blue')->willReturn(true);
        $this->assertEquals($expected, $this->resorce->updateTeam('5c0841aeefd50', '5c0841c053a87', $request));
    }

    public function testCreateLeague()
    {
        $expected = new JsonResponse(["id" => "5c0841c053a87","name" => "manchester united","strip" => "red"], 201);
        $request = new Request([],[],[],[],[],[],json_encode(['name' => 'chelsea', 'strip' => 'blue']));

        $leagueId = LeagueId::create('5c0841aeefd50');
        $league = new League($leagueId, 'Premier');
        $teamId = TeamId::create('5c0841c053a87');
        $team = $league->createTeam($teamId, 'manchester united', 'red');

        $this->leagueService->method('addTeam')->with('5c0841aeefd50', 'chelsea', 'blue')->willReturn($team);

        $this->assertEquals($expected, $this->resorce->createTeam('5c0841aeefd50', $request));
    }

    public function testCreateTeam()
    {
        $expected = new JsonResponse(["id" => "5c0841c053a87","name" => "Premier"], 201);
        $request = new Request([],[],[],[],[],[],json_encode(['name' => 'Premier']));

        $leagueId = LeagueId::create('5c0841c053a87');
        $league = new League($leagueId, 'Premier');

        $this->leagueService->method('createLeague')->with('Premier')->willReturn($league);

        $this->assertEquals($expected, $this->resorce->createLeague($request));
    }

    public function testDeleteLeague()
    {
        $expected = new JsonResponse(['success' => true], 200);

        $this->leagueService->method('deleteLeague')->with('5c0841aeefd50')->willReturn(true);

        $this->assertEquals($expected, $this->resorce->deleteLeague('5c0841aeefd50'));
    }

    public function testNotFoundWhenDeleteLeague()
    {
        $expected = new JsonResponse(['errors' => 'test'], 404);

        $this->leagueService->method('deleteLeague')->with('5c0841aeefd50')->will($this->throwException(new Exception('test')));

        $this->assertEquals($expected, $this->resorce->deleteLeague('5c0841aeefd50'));
    }


    public function testValidationErrorWhenCreateLeague()
    {
        $expected = new JsonResponse(['errors' => 'test'], 422);
        $request = new Request([],[],[],[],[],[],json_encode(['name' => 'Premier']));

        $this->leagueService->method('createLeague')->with('Premier')->will($this->throwException(new Exception('test')));

        $this->assertEquals($expected, $this->resorce->createLeague($request));
    }
}
