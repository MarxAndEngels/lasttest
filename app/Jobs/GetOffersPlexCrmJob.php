<?php
declare(strict_types=1);

namespace App\Jobs;

use App\Constants\Attributes\AttributeName;
use App\Constants\TableConstants;
use App\Dto\PlexCrm\OfferExternalCollectionDto;
use App\Dto\PlexCrm\OfferExternalDto;
use App\Dto\PlexCrm\PaginationDto;
use App\Models\Offer;
use App\Models\Site;
use App\Services\ApiClient\PlexCrmClient;
use App\Services\FileLog\FileLog;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GetOffersPlexCrmJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


  public FileLog $fileLog;

  public int $timeout = 3600;

  public function __construct()
  {
    $this->onQueue('heavy');
    $this->fileLog = new FileLog('carmetrika_upd.log');
  }

  public function handle()
  {
    $siteQuery = Site::query();
    $sites = $siteQuery->whereParentNull()->whereEnabled()->get();
    $sites->each(function ($site){
      $this->fileLog->log('', true);
      $this->fileLog->log('____');
      $this->fileLog->log("Get Offers for site {$site->title}");
      $this->paginate($site);
      $this->fileLog->log("Offers complete");
      $this->clearCacheForSite($site->id);
      $this->fileLog->log("clear cache for site");
//      PostOffersLinkPlexCrmJob::dispatch($site->id, false);
      #dispatch(new PostOffersLinkPlexCrmJob($site->id));
    });
  }

  protected function clearCacheForSite(int $siteId){
    Cache::tags([$siteId, "seoTag.{$siteId}"])->flush();
//    CacheTags::flushTags($siteId);
  }

  protected function paginate($site)
  {
    $plexCrmClient = new PlexCrmClient();
    $currentPage = 1;
    $totalPages = null;
    $datetimeFrom = $site->api_date_from?->toRfc3339String();
    $filter = [
      'page' => $currentPage,
      'all' => 1,
      'limit' => 100
    ];
    if(isset($datetimeFrom) && $datetimeFrom){
      $filter['datetime_from'] = $datetimeFrom;
    }
    $this->fileLog->log('Фильтр:'. json_encode($filter));
    while (!$totalPages || $totalPages >= $currentPage) {
      $filter['page'] = $currentPage;
      $offersExternalArray = $plexCrmClient->getOffers($filter, $site->external_id);
      if(!$offersExternalArray){
        $this->fileLog->log('empty offers');
        $currentPage++;
      }
      $paginationDto = new PaginationDto($offersExternalArray['pagination']);
      $totalPages = $paginationDto->totalPages;
      if ($currentPage == 1) {
        $this->fileLog->log("всего {$paginationDto->totalItems}");
      }
      $this->fileLog->log("страница {$paginationDto->currentPage} из {$paginationDto->totalPages}");
      $this->fileLog->log(collect($offersExternalArray['items'])->pluck('id')->implode(', '));
      $offersExternalArrayItems = $this->validate($offersExternalArray['items']);
      $this->createOffers($offersExternalArrayItems, $site->id);
      $currentPage++;
    }
    $site->api_date_from = Carbon::now()->subHour();
    $site->api_date_last = Carbon::now();
    $site->save();
  }

  protected function createOffers(array $offersExternalArray, int $siteId)
  {
    $offerExternalCollectionDto = OfferExternalCollectionDto::create($offersExternalArray);
    $offerExternalCollectionDtoCollect = collect($offerExternalCollectionDto);
    $offersArr = $offerExternalCollectionDtoCollect->map(fn(OfferExternalDto $offerExternalDto) => $offerExternalDto->getOffer())->all();

    $offerMinArr = $offerExternalCollectionDtoCollect->map(function (OfferExternalDto $offerExternalDto) use ($siteId){
      return [
        AttributeName::EXTERNAL_UNIQUE_ID => $offerExternalDto->uniqueId,
        AttributeName::PRICE => $offerExternalDto->price,
        AttributeName::PRICE_OLD => $offerExternalDto->priceOld,
        AttributeName::IS_ACTIVE => $offerExternalDto->isActive,
        AttributeName::DESCRIPTION => $offerExternalDto->description,
        AttributeName::SITE_ID => $siteId
      ];
    })->all();

    Offer::query()->upsert($offersArr, ['external_unique_id']);

    $offerSiteArr = collect($offerMinArr)->map(function (array $offerMinItem) use ($siteId) {
      $offer = Offer::query()->where('external_unique_id', $offerMinItem['external_unique_id'])->select('id')->first();
      return [
        AttributeName::OFFER_ID => $offer->id,
        AttributeName::SITE_ID => $siteId,
        AttributeName::PRICE => $offerMinItem['price'],
        AttributeName::PRICE_OLD => $offerMinItem['price_old'],
        AttributeName::DESCRIPTION => $offerMinItem['description'] ?: null,
        AttributeName::IS_ACTIVE => $offerMinItem['is_active'],
      ];
    })->all();

    DB::query()->from(TableConstants::OFFER_SITE)->upsert($offerSiteArr, [AttributeName::OFFER_ID, AttributeName::SITE_ID], [AttributeName::PRICE, AttributeName::PRICE_OLD, AttributeName::IS_ACTIVE, AttributeName::DESCRIPTION]);

    //Clear cache
    $offerExternalCollectionDtoCollect
      ->each(fn(OfferExternalDto $offerExternalDto) => Cache::tags("offer.{$offerExternalDto->getExternalId()}")->flush());
  }
  protected function validate(array $offersExternalArray): array
  {
    $rules = [
      'offerType.name' => 'required',
      'offerType.title' => 'required',
      'state.name' => 'required',
      'mark.name' => 'required',
      'mark.title' => 'required',
      'model.name' => 'required',
      'model.title' => 'required',
      'bodyType.name' => 'required',
      'bodyType.title' => 'required',
      'category.name' => 'required',
      'category.title' => 'required',
      'section.name' => 'required',
      'section.title' => 'required',
      'engineType.name' => 'required',
      'engineType.title' => 'required',
      'gearbox.name' => 'required',
      'gearbox.title' => 'required',
      'driveType.name' => 'required',
      'driveType.title' => 'required',
      'color.name' => 'required',
      'color.title' => 'required',
      'wheel.name' => 'required',
      'wheel.title' => 'required',
      'owners.name' => 'required',
      'owners.title' => 'required',
      'images' => ['required','array','min:1'],
      'engineVolume' => 'required',
      'enginePower' => 'required',
      'price' => ['required', 'numeric', 'min:1'],
      'generation.yearBegin' => 'required'
    ];
    return collect($offersExternalArray)->filter(fn($offer) => !Validator::make($offer, $rules)->fails())->all();
  }
}
