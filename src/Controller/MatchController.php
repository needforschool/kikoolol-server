<?php

namespace App\Controller;

use App\Document\MatchDocument;
use App\Service\MatchService;
use App\Service\RiotMatchService;
use App\Helper\HttpResponseHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Match controller.
 * 
 * @Route("/matchs", name="matchs_", methods={"GET"})
 */
class MatchController extends AbstractController
{
  private $service;

  public function __construct(MatchService $matchService)
  {
      $this->service = $matchService;
  }

  /**
   * List all matchs.
   * 
   * @Route("/", name="list", methods={"GET"})
   * 
   * @return JsonResponse
   */
  public function index(): JsonResponse
  {
    $matchs = $this->service->findAll();

    return $this->json(HttpResponseHelper::success($matchs), 200);
  }

  /**
   * List matchs by player id with limit in request payload.
   * 
   * @Route("/{region}/{playerName}", name="list_by_player_name", methods={"GET"})
   * 
   * @param string $region From : europe, asia, americas
   * @param string $playerName
   * 
   * @return JsonResponse
   */
  public function getByPlayerName(string $region, string $playerName, Request $request, RiotMatchService $riotMatchService): JsonResponse
  {
    $limit = $request->query->get('limit', 20);
    $matchs = $this->service->findByPlayerName($playerName, $region, $limit);

    if(empty($matchs)) {
      return $this->json(HttpResponseHelper::error('No matchs found for this player name'), 404);
    }

    return $this->json(HttpResponseHelper::success($matchs), 200);
  }
}
