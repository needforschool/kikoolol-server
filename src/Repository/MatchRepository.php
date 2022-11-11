<?php

namespace App\Repository;

use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class MatchRepository extends DocumentRepository
{
  /**
     * Finds all matchs in the repository matching the given criteria.
     * 
     * @param string $playerUUID,
     * @param int $limit
     *
     * @return array
     */
    public function findAllByPlayerUUID(string $playerUUID, int $limit): mixed
    {
      return $this->createQueryBuilder()
      ->field('metadata.participants')->in([$playerUUID])
      ->limit($limit)
      ->hydrate(false)
      ->getQuery()
      ->execute()
      ->toArray();
    }

  /**
     * Finds all matchs in the repository matching the given criteria.
     * 
     * @param string $playerName,
     * @param int $limit
     *
     * @return array
     */
  public function findAllByPlayerName(string $playerName, int $limit): array
  {
    return $this->createQueryBuilder()
      ->field('playerName')->equals($playerName)
      ->limit($limit)
      ->getQuery()
      ->execute()
      ->toArray();
  }
}