<?php

namespace App\Controller;

use App\Service\SummonerService;
use App\Helper\HttpResponseHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Annotations as OA;

/**
 * Sumonners controller.
 * 
 * @Route("/summoners", name="summoners_")
 * @OA\Tag(name="summoners")
 */
class SummonersController extends AbstractController
{
  private $service;

  public function __construct(SummonerService $summmonerService)
  {
      $this->service = $summmonerService;
  }

  /**
   * List all summoners.
   * 
   * @Route("/", name="list", methods={"GET"})
   * 
   * @return JsonResponse
   */
  public function index()
  {
    $summoners = $this->service->findAll();

    return $this->json(HttpResponseHelper::success($summoners), 200);
  }

  /**
   * Search summoner by name.
   * 
   * @Route("/search/{region}/{summonerName}", name="search_by_name", methods={"GET"})
   * 
   * @param string $region From : europe, asia, americas
   * @param string $summonerName
   * 
   * @return JsonResponse
   */
  public function searchByName(string $region, string $summonerName, Request $request)
  {
    $summoner = $this->service->searchByName($summonerName, $region);

    if(empty($summoner)) {
      return $this->json(HttpResponseHelper::notFound('No summoner found for this name'), 404);
    }

    return $this->json(HttpResponseHelper::success($summoner), 200);
  }
}
