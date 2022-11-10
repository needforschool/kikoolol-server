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
  private $manager;

  public function __construct(DocumentManager $manager)
  {
    $this->manager = $manager;
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
  public function findByPlayerName(RiotMatchService $riotMatchService, string $playerName, int $limit = 20): array
  {
    $matchs = $this->manager->getRepository(MatchDocument::class)->findAllByPlayerName($playerName, $limit);

    return $matchs;
  }

  public function createMatch(MatchDocument $match): MatchDocument
  {
    $this->manager->persist($match);
    $this->manager->flush();

    return $match;
  }
}