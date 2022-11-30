<?php

namespace App\Repository;

use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class SummonerRepository extends DocumentRepository
{
  public function exists(string $name, string $region): bool
  {
    return count(
      $this->createQueryBuilder()
        ->field('summonerName')->equals($name)
        ->field('region')->equals($region)
        ->exists(true)
        ->hydrate(false)
        ->getQuery()
        ->execute()
        ->toArray()
    ) > 0;
  }

  public function searchByName(string $name, string $region): array
  {
    return $this->createQueryBuilder()
      ->field('region')->equals($region)
      ->field('summonerName')->equals(new \MongoDB\BSON\Regex(".{0,}" . $name . ".{0,}"))
      ->hydrate(false)
      ->getQuery()
      ->execute()
      ->toArray();
  }
}