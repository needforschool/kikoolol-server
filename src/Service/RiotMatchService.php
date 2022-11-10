<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Document\MatchDocument;

class RiotMatchService
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
   * Get player account id.
   * 
   * /!\ Check the validity of the region before calling this method.
   * 
   * @param string $playerName
   * @param string $region
   * 
   * @return string
   */
  private function getPUUIDByPlayerName(string $playerName, string $region): string
  {
    $url = sprintf("%s/%s/%s", $this->getPlatformRoute($region), $this->getPlayerInfoEndpoint, $playerName);
    $response = $this->client->request('GET', $url);

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
   * @param string $playerName
   * @param string $region euw1, br1, eun1, jp1, kr, la1, la2, na1, oc1, tr1, ru
   * 
   * @return array<int,MatchDocument>|null
   */
  public function loadAllMatchsByPlayerName(string $playerName, string $region, int $limit): array | null
  {
    $platformRoute = $this->getPlatformRoute($region);

    // - Check if the region is valid
    if(!$platformRoute) {
      return null;
    }

    $matchs = [];

    $puuid = $this->getPUUIDByPlayerName($playerName, $region);
    $matchsIds = $this->getMatchsIdsByPUUID($puuid, $region, $limit);

    foreach ($matchsIds as $matchId) {
      $url = sprintf("%s/%s/%s", $this->getPlatformRoute($region, "v5"), $this->getPlayerMatchsInfoEndpoint, $matchId);
      $response = $this->client->request('GET', $url);

      $data = $response->toArray();
      $matchs[] = new MatchDocument($data);
    }

    return $matchs;
  }
}
