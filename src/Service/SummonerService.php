<?php

namespace App\Service;

use App\Document\SummonerDocument;
use App\Service\RiotAPIService;

use Doctrine\ODM\MongoDB\DocumentManager;

class SummonerService
{
  private $manager, $riotApiService;

  public function __construct(DocumentManager $manager, RiotAPIService $riotApiService)
  {
    $this->manager = $manager;
    $this->riotApiService = $riotApiService;
  }

  public function findAll(): array
  {
    return $this->manager->getRepository(SummonerDocument::class)->findAll();
  }

  public function getPUUIDBySummonerName(string $summonerName, string $region): string
  {
    $summoner = $this->manager->getRepository(SummonerDocument::class)->findOneBy([
      'summonerName' => $summonerName,
      'region' => $region
    ]);

    if($summoner) {
      return $summoner->getPlayerUUID();
    } else {
      return $this->riotApiService->getPUUIDBySummonerName($summonerName, $region);
    }
  }

  public function searchByName(string $name, string $region): array
  {
    return $this->manager->getRepository(SummonerDocument::class)->searchByName($name, $region);
  }

  public function exists(string $name, string $region): bool
  {
    return $this->manager->getRepository(SummonerDocument::class)->exists($name, $region);
  }

  private function hasSummonerNameChanged(string $summonerName, string $playerUUID, string $region): bool
  {
    $summoner = $this->manager->getRepository(SummonerDocument::class)->findOneBy([
      'playerUUID' => $playerUUID,
      'region' => $region
    ]);

    if($summoner) {
      return $summoner->getSummonerName() !== $summonerName;
    }

    return false;
  }

  public function saveSummoner(string $summonerName, string $playerUUID, string $region): void
  {
    if($this->exists($summonerName, $region)) {
      if($this->hasSummonerNameChanged($summonerName, $playerUUID, $region)) {
        $this->updateSummonerNameByPlayerUUID($playerUUID, $summonerName, $region);
      }

      return;
    }

    $summoner = new SummonerDocument();
    $summoner->setSummonerName($summonerName);
    $summoner->setPlayerUUID($playerUUID);
    $summoner->setRegion($region);

    $this->manager->persist($summoner);
    $this->manager->flush();
  }

  private function updateSummonerNameByPlayerUUID(string $playerUUID, string $summonerName, string $region): void
  {
    $summoner = $this->manager->getRepository(SummonerDocument::class)->findOneBy([
      'playerUUID' => $playerUUID,
      'region' => $region
    ]);

    if($summoner) {
      $summoner->setSummonerName($summonerName);
      $this->manager->flush();
    }
  }
}
