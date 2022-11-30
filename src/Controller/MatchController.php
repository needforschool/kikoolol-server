<?php

namespace App\Controller;

use App\Document\MatchDocument;
use App\Service\MatchService;
use App\Helper\HttpResponseHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Annotations as OA;

/**
 * Match controller.
 * 
 * @Route("/matchs", name="matchs_")
 * @OA\Tag(name="matchs")
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
   * @Route("/{region}/{summonerName}", name="list_by_summoner_name", methods={"GET"})
   * 
   * @param string $region From : europe, asia, americas
   * @param string $summonerName
   * 
   * @return JsonResponse
   */
  public function getBySummonerName(string $region, string $summonerName, Request $request): JsonResponse
  {
    $limit = $request->query->get('limit', 20);
    $matchs = $this->service->findBySummonerName($summonerName, $region, $limit);

    if(empty($matchs)) {
      return $this->json(HttpResponseHelper::notFound('No matchs found for this player name'), 404);
    }

    return $this->json(HttpResponseHelper::success($matchs), 200);
  }
}
