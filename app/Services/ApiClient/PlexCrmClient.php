<?php
declare(strict_types=1);
namespace App\Services\ApiClient;
use App\Dto\PlexCrm\AccessTokenDto;
use App\Dto\PlexCrm\FeedbackResponseDto;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class PlexCrmClient
{
  private int $site_id;
  private array $filter;

  private int $client_id;
  private string $client_secret;
  private string $api_url;
  private string $cache_token_key;

  public function __construct()
  {
    $this->client_id = config('plex.client_id');
    $this->client_secret = config('plex.client_secret');
    $this->api_url = config('plex.api_url');
    $this->cache_token_key = config('plex.cache_token_key');
  }

  private function getAccessToken():string
  {
    if(!Cache::has($this->cache_token_key)){
      $response = Http::post("{$this->api_url}oauth/token/", [
        'grant_type' => 'client_credentials',
        'client_id' => $this->client_id,
        'client_secret' => $this->client_secret,
      ]);
      if ($response->successful()) {
        $accessTokenDto = new AccessTokenDto($response->json());
        Cache::put($this->cache_token_key, $accessTokenDto->getAccessToken(), $accessTokenDto->expires_in);
      }
    }
    return Cache::get($this->cache_token_key);
  }

  public function getOffers(array $filter, int $site_id):array
  {
    $offers = Http::retry(3, 1000)
                  ->withToken($this->getAccessToken())
                  ->get("{$this->api_url}api/v2/offers/website/{$site_id}", $filter);
    return $offers->json();
  }

  public function getCommunications(array $filter, int $site_id):Response
  {
    return Http::retry(3, 1000, null, false)->timeout(60)
      ->withToken($this->getAccessToken())
      ->get("{$this->api_url}api/v2/offers/website/{$site_id}/communications", $filter);
  }

  public function sendFeedback($feedbackData) : ?int
  {
    $response = Http::withToken($this->getAccessToken())->post("{$this->api_url}api/v2/feedback/store/", $feedbackData);
    if($response->successful()){
      $feedbackResponseDto = new FeedbackResponseDto($response->json());
      return $feedbackResponseDto->id;
    }else{
      Log::error($response->json());
      return null;
    }
  }

  public function postOffersUrl(array $offerItems) : bool
  {
    $response = Http::withToken($this->getAccessToken())->post("{$this->api_url}api/v2/offers/urls/", $offerItems);
    if($response->successful()){
      return true;
    }else{
      Log::error($response->json());
      return false;
    }
  }
}
