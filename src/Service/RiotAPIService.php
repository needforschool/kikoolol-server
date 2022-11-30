<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Document\MatchDocument;
use App\Document\Metadata;
use App\Document\GameInfo;
use App\Document\Participant;
use App\Document\Item;
use App\Helper\HttpResponseHelper;

class RiotAPIService
{
  private $client;

  private $protocol = "https";
  private $baseUrl = "api.riotgames.com/lol";
  private $platformRoutes;
  private $getPlayerInfoEndpoint = "summoner/v4/summoners/by-name";
  private $getMatchListEndpoint = "match/v4/matchlists/by-account";
  private $getMatchsIdsEndpoint = "match/v5/matches/by-puuid";
  private $getPlayerMatchsInfoEndpoint = "match/v5/matches";

  public function __construct(HttpClientInterface $riotgames)
  { 
      $this->client = $riotgames;

      $platforms = [
        "v4" => [
          "euw1",
          "eun1",
          "na1",
          "br1",
          "la1",
          "la2",
          "oc1",
          "tr1",
          "ru",
          "jp1",
        ],
        "v5" => [
          "americas",
          "asia",
          "europe",
        ]
      ];
      foreach($platforms as $version => $platforms) {
        foreach($platforms as $platform) {
          $this->platformRoutes[$version][$platform] = $this->protocol . "://" . $platform . "." . $this->baseUrl;
        }
      }
  }

  /**
   * Get platform route.
   * 
   * @param string $region
   * 
   * @return string|null
   */
  private function getPlatformRoute(string $region, string $version = "v4"): string | null
  {
    if (!$this->checkRegion($region, $version)) {
      return null;
    }

    if($version == "v5") {
      switch($region) {
        case "euw1":
        case "eun1":
        case "tr1":
        case "ru":
          $region = "europe";
          break;
        case "na1":
        case "br1":
        case "la1":
        case "la2":
        case "oc1":
          $region = "americas";
          break;
        case "jp1":
          $region = "asia";
          break;
        default:
          break;
      }
    }

    return $this->platformRoutes[$version][$region] ?? null;
  }

  /**
   * Check the validity of a given region.
   * 
   * @param string $region
   * 
   * @return bool
   */
  public function checkRegion(string $region): bool
  {
    return array_key_exists($region, $this->platformRoutes["v4"]) || array_key_exists($region, $this->platformRoutes["v5"]);
  }

  /**
   * Get player account id.
   * 
   * /!\ Check the validity of the region before calling this method.
   * 
   * @param string $summonerName
   * @param string $region
   * 
   * @return string
   */
  public function getPUUIDBySummonerName(string $summonerName, string $region): string
  {
    $url = sprintf("%s/%s/%s", $this->getPlatformRoute($region), $this->getPlayerInfoEndpoint, $summonerName);
    $response = $this->client->request('GET', $url);

    if ($response->getStatusCode() != 200) {
      throw new \Exception(HttpResponseHelper::formatErrorFromResponse($response));
    }

    $data = $response->toArray();

    return $data['puuid'];
  }

  /**
   * Get matchs ids for a given player uuid.
   * 
   * @param string $puuid
   * @param string $region
   * @param int $limit
   * 
   * @return array<string>
   */
  private function getMatchsIdsByPUUID(string $puuid, string $region, int $limit): array
  {
    $url = sprintf("%s/%s/%s/ids", $this->getPlatformRoute($region, "v5"), $this->getMatchsIdsEndpoint, $puuid);
    $response = $this->client->request('GET', $url, [
      'query' => [
        'count' => $limit,
      ]
    ]);

    $data = $response->toArray();
    return $data;
  }

  /**
   * Load matchs by player 
   * 
   * @param string $summonerName
   * @param string $region euw1, br1, eun1, jp1, kr, la1, la2, na1, oc1, tr1, ru
   * 
   * @return array<int,MatchDocument>|null
   */
  public function loadAllMatchsByPUUID(string $puuid, string $region, int $limit): array | null
  {
    $platformRoute = $this->getPlatformRoute($region);

    // - Check if the region is valid
    if(!$platformRoute) {
      return null;
    }

    $matchs = [];

    $matchsIds = $this->getMatchsIdsByPUUID($puuid, $region, $limit);

    foreach ($matchsIds as $matchId) {
      $url = sprintf("%s/%s/%s", $this->getPlatformRoute($region, "v5"), $this->getPlayerMatchsInfoEndpoint, $matchId);
      $response = $this->client->request('GET', $url);

      if ($response->getStatusCode() != 200) {
        throw new \Exception(HttpResponseHelper::formatErrorFromResponse($response));
      }

      $data = $response->toArray();

      $metadata = new Metadata($data['metadata']);
      $gameInfo = new GameInfo([
        'id' => $data['info']['gameId'],
        'name' => $data['info']['gameName'],
        'mode' => $data['info']['gameMode'],
        'type' => $data['info']['gameType'],
        'creation' => $data['info']['gameCreation'],
        'duration' => $data['info']['gameDuration'],
        'startTimestamp' => $data['info']['gameStartTimestamp'],
        'endTimestamp' => $data['info']['gameEndTimestamp'],
        'version' => $data['info']['gameVersion'],
        'mapId' => $data['info']['mapId'],
        'platformId' => $data['info']['platformId'],
        'queueId' => $data['info']['queueId'],
        'tournamentCode' => $data['info']['tournamentCode'],
      ]);

      $participants = [];
      for($i = 0; $i < count($data['info']['participants']); $i++) {
        $items = [
          $data['info']['participants'][$i]['item0'],
          $data['info']['participants'][$i]['item1'],
          $data['info']['participants'][$i]['item2'],
          $data['info']['participants'][$i]['item3'],
          $data['info']['participants'][$i]['item4'],
          $data['info']['participants'][$i]['item5'],
          $data['info']['participants'][$i]['item6'],
        ];

        for ($j = 0; $j < count($items); $j++) {
          $items[$j] = new Item([
            'id' => $items[$j],
            'position' => $j,
          ]);
        }

        $participant = array_merge($data['info']['participants'][$i], [
          'items' => $items,
        ]);

        $participant = new Participant($participant);
        array_push($participants, $participant);
      }
      
      // TODO: Hydrate teams

      $match = new MatchDocument();
      $match
        ->setMetadata($metadata)
        ->setGameInfo($gameInfo)
        ->setParticipants($participants)
        ->setTeams([]);

      array_push($matchs, $match);
    }

    return $matchs;
  }
}
