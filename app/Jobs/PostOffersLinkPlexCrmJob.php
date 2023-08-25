<?php
declare(strict_types=1);

namespace App\Jobs;

use App\Constants\Attributes\AttributeName;
use App\Models\Offer;
use App\Models\Site;
use App\QueryBuilders\OfferQueryBuilder;
use App\QueryBuilders\SiteQueryBuilder;
use App\Services\ApiClient\PlexCrmClient;
use App\Services\FileLog\FileLog;
use Illuminate\Bus\Queueable;
use App\Dto\Feeds\OfferFeedCollectionDto;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PostOffersLinkPlexCrmJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


  public function __construct(
    public ?int $siteId = null,
    public ?bool $allOffers = false,
    public FileLog $fileLog = new FileLog('plex_crm_urls_log.log'),
    public PlexCrmClient $plexCrmClient = new PlexCrmClient(),
  )
  {
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle(): void
  {
    $siteQuery = Site::query();
    $siteQuery
      ->when($this->siteId, fn(SiteQueryBuilder $query) =>
        $query->where('id', $this->siteId))
      ->when(!$this->siteId, fn(SiteQueryBuilder $query) =>
          $query->whereParentNull()->wherePostLinkCrm()->whereEnabled())
      ->with('childrenSites')
      ->get()
      ->each(function (Site $site) {
        $this->makeOffersForPlex($site);
      });
  }

  private function makeOffersForPlex(Site $site): void
  {
    $dateFrom = $this->allOffers ? null : $site->api_date_from;

    $offersArr = Offer::query()
                      ->when($dateFrom, fn(OfferQueryBuilder $q) =>
                        $q->whereUpdatedAtFrom($dateFrom))
                          ->withPriceForSite($site->id, $site->filter, false, true)
                          ->selectForPlexCrmUrl()
                          ->get()
                          ->toArray();

    $this->fileLog->log('', true);
    $this->fileLog->log('____');
    $this->fileLog->log("Post offers from site {$site->title}");
    $this->postOffersLink($offersArr, $site->url, $site->category_url, $site->external_id, $site->generation_url, $site->category_association);

    $site->childrenSites?->each(function (Site $childrenSite) use ($offersArr){
      $this->fileLog->log('____');
      $this->fileLog->log("Post offers from site {$childrenSite->title}");
      $this->postOffersLink($offersArr, $childrenSite->url, $childrenSite->category_url, $childrenSite->external_id, $childrenSite->generation_url, $childrenSite->category_association);
    });
  }

  private function postOffersLink(array $offersArr, string $siteUrl, string $categoryUrl, int $siteExternalId, bool $urlWithGeneration, array $categoryAssociation):void
  {
    $offersArrayUrl = offerFeedCollectionDto::getOffersArrayLinkForPlexCrm($offersArr, $siteUrl, $categoryUrl, $siteExternalId, $urlWithGeneration, $categoryAssociation);
    #Отправка ссылок в CRM
    $offersArrayUrlCollect = collect($offersArrayUrl);
    $this->fileLog->log("count: {$offersArrayUrlCollect->count()} offers");
    $offersArrayUrlCollect->chunk(1000)->each(fn($offers) => $this->plexCrmClient->postOffersUrl(['items' => $offers->all()]));
    $this->fileLog->log("Posted links to CRM");
  }

//  private function getMainSite(Site $site): array
//  {
//    if ($site->parent_site_id){
//      return Site::query()->select([AttributeName::ID, AttributeName::API_DATE_FROM])->whereId($site->parent_site_id)->first()->toArray();
//    }else{
//      return $site->toArray();
//    }
//  }
}
