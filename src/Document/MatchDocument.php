<?php

namespace App\Document;

use App\Repository\MatchRepository;
use App\Util\Hydrateable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\ODM\MongoDB\PersistentCollection;

/**
 * @ODM\Document(collection="matchs", repositoryClass=MatchRepository::class)
 */
class MatchDocument
{
  /**
   * @ODM\Id
   */
  protected $id;

  /**
   * @ODM\EmbedOne(targetDocument=Metadata::class)
   */
  protected $metadata;

  /**
   * @ODM\EmbedOne(targetDocument=GameInfo::class)
   */
  protected $gameInfo;

  /**
   * @ODM\EmbedMany(targetDocument=Participant::class)
   */
  protected $participants;

  /**
   * @ODM\EmbedMany(targetDocument=Team::class)
   */
  protected $teams;

  public function getMetadata(): Metadata
  {
    return $this->metadata;
  }

  public function setMetadata(Metadata $metadata): self
  {
    $this->metadata = $metadata;

    return $this;
  }

  public function getGameInfo(): GameInfo
  {
    return $this->gameInfo;
  }

  public function setGameInfo(GameInfo $gameInfo): self
  {
    $this->gameInfo = $gameInfo;

    return $this;
  }

  public function getParticipants(): PersistentCollection
  {
    return $this->participants;
  }

  public function setParticipants(array $participants): self
  {
    $this->participants = $participants;

    return $this;
  }

  public function getTeams(): PersistentCollection
  {
    return $this->teams;
  }

  public function setTeams(array $teams): self
  {
    $this->teams = $teams;

    return $this;
  }
}

/**
 * @ODM\EmbeddedDocument
 */
class Metadata extends Hydrateable
{
  /**
   * @ODM\Field(type="string")
   */
  protected $dataVersion;

  /**
   * @ODM\Field(type="string")
   */
  protected $matchId;

  /**
   * @ODM\Field(type="collection")
   */
  protected $participants;
}

/**
 * @ODM\EmbeddedDocument
 */
class GameInfo extends Hydrateable {
  /**
   * @ODM\Field(type="int")
   */
  protected $id;
  
  /**
   * @ODM\Field(type="string")
   */
  protected $name;
  
  /**
   * @ODM\Field(type="string")
   */
  protected $mode;
  
  /**
   * @ODM\Field(type="string")
   */
  protected $type;

  /**
   * @ODM\Field(type="int")
   */
  protected $creation;

  /**
   * @ODM\Field(type="int")
   */
  protected $duration;
  
  /**
   * @ODM\Field(type="int")
   */
  protected $startTimestamp;
  
  /**
   * @ODM\Field(type="int")
   */
  protected $endTimestamp;
  
  /**
   * @ODM\Field(type="string")
   */
  protected $version;
  
  /**
   * @ODM\Field(type="int")
   */
  protected $mapId;
  
  /**
   * @ODM\Field(type="string")
   */
  protected $platformId;
  
  /**
   * @ODM\Field(type="int")
   */
  protected $queueId;
  
  /**
   * @ODM\Field(type="string")
   */
  protected $tournamentCode;
}

/**
 * @ODM\EmbeddedDocument
 */
class Participant extends Hydrateable
{
  /**
   * @ODM\Field(type="string")
   */
  protected $puuid;

  /**
   * @ODM\Field(type="int")
   */
  protected $id;

  /**
   * @ODM\Field(type="string")
   */
  protected $summonerId;

  /**
   * @ODM\Field(type="int")
   */
  protected $summonerLevel;

  /**
   * @ODM\Field(type="string")
   */
  protected $summonerName;

  /**
   * @ODM\Field(type="int")
   */
  protected $championId;

  /**
   * @ODM\Field(type="string")
   */
  protected $championName;

  /**
   * @ODM\Field(type="string")
   */
  protected $championIconUrl;

  /**
   * @ODM\Field(type="int")
   */
  protected $champExperience;

  /**
   * @ODM\Field(type="int")
   */
  protected $champLevel;

  /**
   * @ODM\Field(type="int")
   */
  protected $champTransform;

  /**
   * @ODM\Field(type="string")
   */
  protected $individualPosition;

  /**
   * @ODM\Field(type="string")
   */
  protected $lane;

  /**
   * @ODM\Field(type="int")
   */
  protected $killingSprees;

  /**
   * @ODM\Field(type="int")
   */
  protected $kills;

  /**
   * @ODM\Field(type="int")
   */
  protected $assists;

  /**
   * @ODM\Field(type="int")
   */
  protected $deaths;

  /**
   * @ODM\Field(type="int")
   */
  protected $dragonKills;

  /**
   * @ODM\Field(type="int")
   */
  protected $baronKills;

  /**
   * @ODM\Field(type="int")
   */
  protected $basicPings;

  /**
   * @ODM\Field(type="int")
   */
  protected $bountyLevel;

  /**
   * @ODM\EmbedMany(targetDocument=Item::class)
   */
  protected $items;

  /**
   * @ODM\Field(type="int")
   */
  protected $itemsPurchased;

  /**
   * @ODM\Field(type="int")
   */
  protected $consumablesPurchased;

  /**
   * @ODM\Field(type="int")
   */
  protected $goldEarned;

  /**
   * @ODM\Field(type="int")
   */
  protected $goldSpent;

  /**
   * @ODM\Field(type="bool")
   */
  protected $firstBloodKill;

  /**
   * @ODM\Field(type="bool")
   */
  protected $firstBloodAssist;

  /**
   * @ODM\Field(type="bool")
   */
  protected $firstTowerKill;

  /**
   * @ODM\Field(type="bool")
   */
  protected $firstTowerAssist;

  /**
   * @ODM\Field(type="int")
   */
  protected $inhibitorKills;

  /**
   * @ODM\Field(type="int")
   */
  protected $inhibitorTakedowns;

  /**
   * @ODM\Field(type="int")
   */
  protected $inhibitorsLost;

  /**
   * @ODM\Field(type="int")
   */
  protected $nexusKills;

  /**
   * @ODM\Field(type="int")
   */
  protected $nexusLost;

  /**
   * @ODM\Field(type="int")
   */
  protected $nexusTakedowns;

  /**
   * @ODM\Field(type="bool")
   */
  protected $gameEndedInEarlySurrender;

  /**
   * @ODM\Field(type="bool")
   */
  protected $gameEndedInSurrender;
}

/**
 * @ODM\EmbeddedDocument
 */
class Item extends Hydrateable
{
  /**
   * @ODM\Field(type="int")
   */
  protected $id;

  /**
   * @ODM\Field(type="int")
   */
  protected $position;
}

class Timeline
{
  /***
   * @ODM\Field(type="int")
   */
  protected $frameInterval;

  /**
   * @ODM\EmbedMany(targetDocument=TimelineEvent::class)
   */
  protected $events;
}

class TimelineEvent
{
  /**
   * @ODM\Field(type="int")
   */
  protected $participantId;

  /**
   * @ODM\Field(type="int")
   */
  protected $timestamp;

  /**
   * @ODM\Field(type="string")
   */
  protected $type;
}
