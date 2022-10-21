<?php

namespace App\Service;

use App\Document\MatchDocument;
use App\Service\RiotMatchService;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * Match service.
 */
class MatchService
{
  private $manager, $riotMatchService;

  public function __construct(DocumentManager $manager, RiotMatchService $riotMatchService)
  {
    $this->manager = $manager;
    $this->riotMatchService = $riotMatchService;
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
   * @param string $playerName
   * @param int $limit Optional
   * 
   * @return array
   */
  public function findByPlayerName(string $playerName, string $region, int $limit = 20): array
  {
    $matchs = $this->manager->getRepository(MatchDocument::class)->findAllByPlayerName($playerName, $limit);

    if(!$matchs) {
      $matchs = $this->riotMatchService->loadAllMatchsByPlayerName($playerName, $region, $limit);

      if($matchs) {
        foreach($matchs as $match) {
          $this->manager->persist($match);
        }

        $this->manager->flush();
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