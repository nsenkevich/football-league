<?php
declare(strict_types=1);

namespace Championship\Application\Resource;

use Championship\Application\Service\League\LeagueService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class LeagueResource extends ApiResource
{
    /**
     * @var LeagueService
     */
    private $leagueService;

    /**
     * @param LeagueService $leagueService
     */
    public function __construct(LeagueService $leagueService)
    {
        $this->leagueService = $leagueService;
    }

    /**
     * @Route("/league", methods="POST")
     */
    public function createLeague(Request $request)
    {
        $request = $this->transformJsonBody($request);
        if (! $request) {
            return $this->respondValidationError('Please provide a valid request!');
        }

        try {
            $league = $this->leagueService->createLeague($request->get('name'));
            return $this->respondCreated(['id' => (string) $league->id(),'name' => $league->name()]);
        } catch (\Exception $exception) {
            return $this->respondValidationError($exception->getMessage());
        }
    }

    /**
     * @Route("/league/{id}", methods="DELETE")
     */
    public function deleteLeague($id)
    {
        try {
            $this->leagueService->deleteLeague($id);
            return $this->respond(['success' => true]);
        } catch (\Exception $exception) {
            return $this->respondNotFound($exception->getMessage());
        }
    }

    /**
     * @Route("/league/{id}/teams", methods="GET")
     */
    public function fetchTeams($id)
    {
        try {
            $teams = $this->leagueService->getTeams($id);
            return $this->respond($teams);
        } catch (\Exception $exception) {
            return $this->respondNotFound($exception->getMessage());
        }
    }

    /**
     * @Route("/league/{id}/teams", methods="POST")
     */
    public function createTeam(string $id, Request $request)
    {
        $request = $this->transformJsonBody($request);
        if (! $request) {
            return $this->respondValidationError('Please provide a valid request!');
        }

        try {
            $team = $this->leagueService->addTeam($id, $request->get('name'), $request->get('strip'));
            return $this->respondCreated(['id' => (string) $team->id(),'name' => $team->name(),'strip' => $team->strip()]);
        } catch (\Exception $exception) {
            return $this->respondNotFound($exception->getMessage());
        }
    }

    /**
     * @Route("/league/{leagueId}/teams/{teamId}", methods="PUT")
     */
    public function updateTeam(string $leagueId, string $teamId, Request $request)
    {
        $request = $this->transformJsonBody($request);
        if (! $request) {
            return $this->respondValidationError('Please provide a valid request!');
        }
        try {
            $this->leagueService->updateTeam($leagueId, $teamId, $request->get('name'), $request->get('strip'));
            return $this->respond(['success' => true]);
        } catch (\Exception $exception) {
            return $this->respondNotFound($exception->getMessage());
        }
    }
}
