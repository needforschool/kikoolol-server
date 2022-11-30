<?php

namespace App\Document;

use App\Repository\SummonerRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(collection="summoners", repositoryClass=SummonerRepository::class)
 */
class SummonerDocument
{
  /**
   * @ODM\Id
   */
  protected $id;

  /**
   * @ODM\Field(type="string")
   * @ODM\Index(order="asc")
   */
  protected $summonerName;

  /**
   * @ODM\Field(type="string")
   */
  protected $playerUUID;

  /**
   * @ODM\Field(type="string")
   */
  protected $region;

  public function getSummonerName(): string
  {
    return $this->summonerName;
  }

  public function setSummonerName(string $summonerName): self
  {
    $this->summonerName = $summonerName;

    return $this;
  }

  public function getPlayerUUID(): string
  {
    return $this->playerUUID;
  }

  public function setPlayerUUID(string $playerUUID): self
  {
    $this->playerUUID = $playerUUID;

    return $this;
  }

  public function getRegion(): string
  {
    return $this->region;
  }

  public function setRegion(string $region): self
  {
    $this->region = $region;

    return $this;
  }
}