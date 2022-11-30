<?php

namespace App\Service;

use App\Document\MatchDocument;
use App\Service\RiotAPIService;
use App\Service\SummonerService;
use App\Helper\HttpResponseHelper;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * Match service.
 */
class MatchService
{
  private $manager, $riotApiService, $summonerService;

  public function __construct(DocumentManager $manager, RiotAPIService $riotApiService, SummonerService $summonerService)
  {
    $this->manager = $manager;
    $this->riotApiService = $riotApiService;
    $this->summonerService = $summonerService;
  }

  /**
   * Find all matchs.
   * 
   * @return array
   */
  public function findAll(): array
  {
    return $this->manager->getRepository(MatchDocument::class)->findAll();
  }

  /**
   * Find matchs by player id.
   * 
   * @param string $summonerName
   * @param int $limit Optional
   * 
   * @return array
   */
  public function findBySummonerName(string $summonerName, string $region, int $limit = 20): array
  {
    if (!$this->riotApiService->checkRegion($region)) {
      throw new NotFoundHttpException("Region not found");
    }

    $playerUUID = $this->summonerService->getPUUIDBySummonerName($summonerName, $region);
    $this->summonerService->saveSummoner($summonerName, $playerUUID, $region);
    $matchs = $this->manager->getRepository(MatchDocument::class)->findAllByPlayerUUID($playerUUID, $limit);

    if(!$matchs) {
      $matchs = $this->riotApiService->loadAllMatchsByPUUID($playerUUID, $region, $limit);

      if($matchs) {
        foreach($matchs as $match) {
          $this->manager->persist($match);
        }

        $this->manager->flush();
      }
    }

    for ($i = 0; $i < count($matchs); $i++) {
      $match = (array) $matchs[$i];

      foreach ($match["participants"] as $participant) {
        $this->summonerService->saveSummoner($participant["summonerName"], $participant["puuid"], $region);
      }
    }

    return $matchs;
  }

  public function createMatch(MatchDocument $match): MatchDocument
  {
    $this->manager->persist($match);
    $this->manager->flush();

    return $match;
  }
}