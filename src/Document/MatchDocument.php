<?php

namespace App\Document;

use App\Repository\MatchRepository;
use App\Utils\Hydrateable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="matchs", repositoryClass=MatchRepository::class)
 */
class MatchDocument extends Hydrateable
{
  /**
   * @MongoDB\Id
   */
  protected $id;

  /**
   * @MongoDB\Field(type="string")
   */
  protected $matchId;

  /**
   * @MongoDB\Field(type="hash")
   */
  protected $participtants;

  public function getId(): ?string
  {
    return $this->id;
  }

  public function getMatchId(): ?string
  {
    return $this->matchId;
  }

  public function setMatchId(string $matchId): self
  {
    $this->matchId = $matchId;

    return $this;
  }

  public function getParticiptants(): ?array
  {
    return $this->participtants;
  }

  public function setParticiptants(array $participtants): self
  {
    $this->participtants = $participtants;

    return $this;
  }
}