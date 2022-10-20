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
  private $getMatchsIdsEndpoint = "match/v5/matches/by-puuid/%s/ids";
  private $getPlayerMatchsInfo = "match/v5/matches";

  public function __construct(HttpClientInterface $riotgames)
  { 
      $this->client = $riotgames;

      $platforms = [
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
      ];
      foreach ($platforms as $platform) {
        $this->platformRoutes[$platform] = $platform.".".$this->baseUrl;
      }
  }

  /**
   * Get platform route.
   * 
   * @param string $region
   * 
   * @return string|null
   */
  private function getPlatformRoute(string $region): string | null
  {
    return array_key_exists($region, $this->platformRoutes) ? $this->platformRoutes[$region] : null;
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
  private function getAccountIdByPlayerName(string $playerName, string $region): string
  {
    $url = sprintf("%s://%s/%s/%s", $this->protocol, $this->getPlatformRoute($region), $this->getPlayerInfoEndpoint, $playerName);
    $response = $this->client->request('GET', $url);

    $data = $response->toArray();
    return $data['accountId'];
  }

  /**
   * Load matchs by player 
   * 
   * @param string $playerName
   * @param string $region euw1, br1, eun1, jp1, kr, la1, la2, na1, oc1, tr1, ru
   * 
    * @return array<int,MatchDocument>|null
   */
  public function loadAllMatchsByPlayerName(string $playerName, string $region): array | null
  {
    $platformRoute = $this->getPlatformRoute($region);

    if(!$platformRoute) {
      return null;
    }

    $accountId = $this->getAccountIdByPlayerName($playerName, $region);

    $url = sprintf("%s://%s/%s/%s", $this->protocol, $platformRoute, $this->getMatchListEndpoint, $accountId);
    $response = $this->client->request('GET', $url);

    if($response->getStatusCode() !== 200) {
      return null;
    }

    $data = $response->toArray();
    foreach ($data as $key => $value) {
      $match = new MatchDocument();
      print_r($value);
    }

    return $data;
  }
}