<?php

namespace App\ModelFilters;

use App\Constants\Attributes\AttributeName;
use App\Models\Site;
use App\QueryBuilders\OfferQueryBuilder;
use App\Services\Filter\GetExternalIdArrayService;
use EloquentFilter\ModelFilter;
use Illuminate\Support\Facades\Cache;
class OfferFilter extends ModelFilter
{
  public $relations = [];
  protected $query;

  public function external(int $externalId): OfferQueryBuilder
  {
    return $this->query->whereExternalId($externalId);
  }
  public function externalIdArray (array $externalIdArray): OfferQueryBuilder
  {
    return $this->query->whereExternalIdArray($externalIdArray);
  }
  public function exceptExternal(int $externalId): OfferQueryBuilder
  {
    return $this->query->whereExcept(AttributeName::EXTERNAL_ID, $externalId);
  }

  public function exceptExternalIdCsv(string $fileName): ?OfferQueryBuilder
  {
    $externalIdArray = (new GetExternalIdArrayService($fileName))->getExternalIdArray();
    if ($externalIdArray){
      return $this->query->whereExceptArray(AttributeName::EXTERNAL_ID, $externalIdArray);
    }else{
      return null;
    }
  }
  public function typeEnum(string $typeEnum): OfferQueryBuilder
  {
    return $this->query->whereTypeEnum($typeEnum);
  }
  public function typeEnumArray(array $typeEnumArray): OfferQueryBuilder
  {
    return $this->query->whereTypeEnumArray($typeEnumArray);
  }
  public function mark(int $markId): OfferQueryBuilder
  {
    return $this->query->whereMarkId($markId);
  }
  public function markSlug(string $markSlug): OfferQueryBuilder
  {
    return $this->query->whereMarkSlug($markSlug);
  }
  public function markSlugArray(array $markSlugArray): OfferQueryBuilder
  {
    return $this->query->whereMarkSlugArray($markSlugArray);
  }
  public function folder(int $folderId): OfferQueryBuilder
  {
    return $this->query->whereFolderId($folderId);
  }
  public function folderSlug(string $folderSlug): OfferQueryBuilder
  {
    return $this->query->whereFolderSlug($folderSlug);
  }
  public function folderSlugArray(array $folderSlugArray): OfferQueryBuilder
  {
    return $this->query->whereFolderSlugArray($folderSlugArray);
  }
  public function generation(int $generationId): OfferQueryBuilder
  {
    return $this->query->whereGenerationId($generationId);
  }
  public function generationSlug(string $generationSlug): OfferQueryBuilder
  {
    return $this->query->whereGenerationSlug($generationSlug);
  }
  public function generationSlugArray(array $generationSlugArray): OfferQueryBuilder
  {
    return $this->query->whereGenerationSlugArray($generationSlugArray);
  }
  public function gearbox(int $gearboxId): OfferQueryBuilder
  {
    return $this->query->whereGearboxId($gearboxId);
  }
  public function gearboxIdArray(array $gearboxIdArray): OfferQueryBuilder
  {
    return $this->query->whereGearboxIdArray($gearboxIdArray);
  }
  public function bodyType(int $bodyTypeId): OfferQueryBuilder
  {
    return $this->query->whereBodyTypeId($bodyTypeId);
  }
  public function bodyTypeIdArray(array $bodyTypeIdArray): OfferQueryBuilder
  {
    return $this->query->whereBodyTypeIdArray($bodyTypeIdArray);
  }
  public function driveType(int $driveTypeId): OfferQueryBuilder
  {
    return $this->query->whereDriveTypeId($driveTypeId);
  }
  public function driveTypeIdArray(array $driveTypeIdArray): OfferQueryBuilder
  {
    return $this->query->whereDriveTypeIdArray($driveTypeIdArray);
  }
  public function engineType(int $engineTypeId): OfferQueryBuilder
  {
    return $this->query->whereEngineTypeId($engineTypeId);
  }
  public function engineTypeIdArray(array $engineTypeIdArray): OfferQueryBuilder
  {
    return $this->query->whereEngineTypeIdArray($engineTypeIdArray);
  }
  public function owner(int $ownerId): OfferQueryBuilder
  {
    return $this->query->whereOwnerId($ownerId);
  }
  public function ownerIdArray(array $ownerIdArray): OfferQueryBuilder
  {
    return $this->query->whereOwnerIdArray($ownerIdArray);
  }
  public function communicationsCountFrom(int $count): OfferQueryBuilder
  {
    return $this->query->whereCommunicationsCountFrom($count);
  }
  public function yearFrom(int $yearFrom): OfferQueryBuilder
  {
    return $this->query->whereYearFrom($yearFrom);
  }
  public function yearTo(int $yearTo): OfferQueryBuilder
  {
    return $this->query->whereYearTo($yearTo);
  }
  public function priceFrom(int $priceFrom): OfferQueryBuilder
  {
    return $this->query->wherePriceFrom($priceFrom);
  }
  public function priceTo(int $priceTo): OfferQueryBuilder
  {
    return $this->query->wherePriceTo($priceTo);
  }
  public function runFrom(int $runFrom): OfferQueryBuilder
  {
    return $this->query->whereRunFrom($runFrom);
  }
  public function runTo(int $runTo): OfferQueryBuilder
  {
    return $this->query->whereRunTo($runTo);
  }
  public function site(array $site): OfferQueryBuilder
  {
    $siteQuery = Site::query();
    $currentSiteArray = Cache::rememberForever("site.{$site['id']}", fn() => $siteQuery->selectForOfferPrice()->whereId($site['id'])->first()->toArray());
    if($currentSiteArray[AttributeName::PARENT_SITE_ID]){
      $parentSiteId = $currentSiteArray[AttributeName::PARENT_SITE_ID];
    }else{
      $parentSiteId = $currentSiteArray[AttributeName::ID];
    }
    return $this->query->withPriceForSite($parentSiteId, $currentSiteArray[AttributeName::FILTER], $site['onlyActive'], false, $currentSiteArray[AttributeName::ID]);
  }
  public function minYear(int $year): OfferQueryBuilder
  {
    return $this->query->whereMinYear($year);
  }
  public function category(string $category): OfferQueryBuilder
  {
    return $this->query->whereCategory($category);
  }
  public function dealer(int $dealerId): OfferQueryBuilder
  {
    return $this->query->whereDealerId($dealerId);
  }
  public function dealerIdArray(array $dealerIdArray): OfferQueryBuilder
  {
    return $this->query->whereDealerIdArray($dealerIdArray);
  }
  public function sort(string $value): OfferQueryBuilder
  {
    $sort = explode('|', $value);
    $map = [
      'created_at' => 'offers.created_at',
      'price' => 'offer_site.price',
      'run' => 'offers.run',
      'year' => 'offers.year',
      'communications_count' => 'offers.communications_count'
    ];
    return $this->query->orderBy($map[$sort[0]] ?? $map['price'], $sort[1] ?? 'asc');
  }
}
