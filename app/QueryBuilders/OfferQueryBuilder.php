<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Constants\Attributes\AttributeName;
use App\Constants\TableConstants;
use App\ModelFilters\OfferCountableFilter;
use App\ModelFilters\OfferFilter;
use App\Models\Folder;
use App\Models\Generation;
use App\Models\Mark;
use App\Models\PriceOldSite;
use App\Models\Site;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;

final class OfferQueryBuilder extends Builder
{
  use Filterable;

  protected function modelCountableFilter(): string
  {
    return OfferCountableFilter::class;
  }

  protected function modelFilter(): string
  {
    return OfferFilter::class;
  }

  public function selectForSeoTags(): self
  {
    return $this->select(
      AttributeName::EXTERNAL_ID, AttributeName::MARK_ID, AttributeName::FOLDER_ID,
      AttributeName::MODIFICATION_ID, AttributeName::ENGINE_TYPE_ID, AttributeName::GEARBOX_ID, AttributeName::ENGINE_VOLUME,
      AttributeName::ENGINE_POWER, AttributeName::RUN, AttributeName::YEAR, AttributeName::PRICE)
      ->with(['mark', 'folder', 'modification', 'engineType', 'gearbox']);
  }

  public function selectForYandexXmlFeed(): self
  {
    $select = collect([AttributeName::MARK_ID, AttributeName::FOLDER_ID, AttributeName::MODIFICATION_ID, AttributeName::GENERATION_ID,
      AttributeName::BODY_TYPE_ID, AttributeName::WHEEL_ID, AttributeName::COLOR_ID, AttributeName::OWNER_ID,
      AttributeName::ENGINE_VOLUME, AttributeName::ENGINE_POWER, AttributeName::RUN, AttributeName::YEAR, AttributeName::CATEGORY_ENUM,
      AttributeName::EXTERNAL_ID, AttributeName::PRICE, AttributeName::IMAGES, AttributeName::VIN]);
    $selectArray = $select->map(fn($item) => $item !== AttributeName::PRICE ? $this->qualifyColumn($item) : $item)->all();
    return $this
      ->select($selectArray)
      ->with(['mark', 'folder', 'modification', 'generation', 'bodyType', 'color', 'owner']);
  }

  public function selectForTelegramFeed(): self
  {
    $select = collect([AttributeName::MARK_ID, AttributeName::FOLDER_ID, AttributeName::MODIFICATION_ID, AttributeName::GENERATION_ID,
      AttributeName::BODY_TYPE_ID, AttributeName::WHEEL_ID, AttributeName::COLOR_ID, AttributeName::OWNER_ID, AttributeName::DRIVE_TYPE_ID,
      AttributeName::ENGINE_VOLUME, AttributeName::ENGINE_POWER, AttributeName::RUN, AttributeName::YEAR, AttributeName::CATEGORY_ENUM,
      AttributeName::EXTERNAL_ID, AttributeName::PRICE, AttributeName::IMAGES]);
    $selectArray = $select->map(fn($item) => $item !== AttributeName::PRICE ? $this->qualifyColumn($item) : $item)->all();
    return $this
      ->select($selectArray)
      ->with(['mark', 'folder', 'modification', 'generation', 'bodyType', 'wheel', 'color', 'owner', 'driveType']);
  }

  public function selectForYandexYmlCatalogFeed(): self
  {
    $select = collect([AttributeName::NAME, AttributeName::MARK_ID, AttributeName::FOLDER_ID, AttributeName::MODIFICATION_ID,
      AttributeName::BODY_TYPE_ID, AttributeName::DRIVE_TYPE_ID, AttributeName::ENGINE_TYPE_ID, AttributeName::GEARBOX_ID,
      AttributeName::ENGINE_VOLUME, AttributeName::ENGINE_POWER, AttributeName::RUN, AttributeName::YEAR, AttributeName::CATEGORY_ENUM,
      AttributeName::EXTERNAL_ID, AttributeName::PRICE, AttributeName::IMAGES, AttributeName::GENERATION_ID]);
    $selectArray = $select->map(fn($item) => $item !== AttributeName::PRICE ? $this->qualifyColumn($item) : $item)->all();
    return $this
      ->select($selectArray)
      ->with(['mark', 'folder', 'modification', 'bodyType', 'driveType', 'engineType', 'gearbox', 'generation']);
  }
  public function selectForYandexYmlFeed(): self
  {
    $select = collect([AttributeName::NAME, AttributeName::MARK_ID, AttributeName::FOLDER_ID, AttributeName::CATEGORY_ENUM,
      AttributeName::YEAR, AttributeName::EXTERNAL_ID, AttributeName::PRICE, AttributeName::IMAGES, AttributeName::GENERATION_ID]);
    $selectArray = $select->map(fn($item) => $item !== AttributeName::PRICE ? $this->qualifyColumn($item) : $item)->all();
    return $this
      ->select($selectArray)
      ->with(['mark', 'folder', 'generation']);
  }
  public function selectForSitemap(): self
  {
    $select = [$this->qualifyColumn(AttributeName::ID), $this->qualifyColumn(AttributeName::EXTERNAL_ID),
      $this->qualifyColumn(AttributeName::FOLDER_ID), $this->qualifyColumn(AttributeName::MARK_ID),
      $this->qualifyColumn(AttributeName::GENERATION_ID), $this->qualifyColumn(AttributeName::CATEGORY_ENUM)];
    return $this
      ->select($select)
      ->with(['mark', 'folder', 'generation']);
  }

  public function selectForGoogleXmlFeed(): self
  {
    return $this
      ->select(
        $this->qualifyColumn(AttributeName::MARK_ID), $this->qualifyColumn(AttributeName::FOLDER_ID),
        $this->qualifyColumn(AttributeName::GENERATION_ID), $this->qualifyColumn(AttributeName::YEAR),
        $this->qualifyColumn(AttributeName::CATEGORY_ENUM),
        $this->qualifyColumn(AttributeName::EXTERNAL_ID), AttributeName::PRICE, $this->qualifyColumn(AttributeName::IMAGES))
      ->with(['mark', 'folder', 'generation']);
  }

  public function selectForVkXmlFeed(): self
  {
    $select = collect([AttributeName::MARK_ID, AttributeName::FOLDER_ID, AttributeName::GENERATION_ID,
      AttributeName::BODY_TYPE_ID, AttributeName::COLOR_ID, AttributeName::OWNER_ID,
      AttributeName::ENGINE_VOLUME, AttributeName::ENGINE_POWER, AttributeName::RUN, AttributeName::YEAR, AttributeName::CATEGORY_ENUM,
      AttributeName::EXTERNAL_ID, AttributeName::PRICE, AttributeName::IMAGES, AttributeName::VIN]);
    $selectArray = $select->map(fn($item) => $item !== AttributeName::PRICE ? $this->qualifyColumn($item) : $item)->all();
    return $this
      ->select($selectArray)
      ->with(['mark', 'folder', 'generation', 'bodyType', 'color', 'owner']);
  }

  public function selectForPlexCrmUrl(): self
  {
    return $this
      ->select(
        $this->qualifyColumn(AttributeName::ID), AttributeName::MARK_ID, AttributeName::FOLDER_ID, AttributeName::GENERATION_ID,
        AttributeName::YEAR, AttributeName::EXTERNAL_ID, AttributeName::IS_ACTIVE, AttributeName::CATEGORY_ENUM)
      ->with(['mark', 'folder', 'generation']);
  }

  public function selectForOfferReport(): self
  {
    return $this
      ->select(
        $this->qualifyColumn(AttributeName::EXTERNAL_ID), $this->qualifyColumn(AttributeName::ENGINE_VOLUME),
        $this->qualifyColumn(AttributeName::MARK_ID), $this->qualifyColumn(AttributeName::FOLDER_ID),
        $this->qualifyColumn(AttributeName::GEARBOX_ID), $this->qualifyColumn(AttributeName::YEAR),
        $this->qualifyColumn(AttributeName::ENGINE_POWER), $this->qualifyColumn(AttributeName::TYPE_ENUM),
        $this->qualifyColumn(AttributeName::DEALER_ID), $this->qualifyColumn(AttributeName::GENERATION_ID),
        AttributeName::CATEGORY_ASSOCIATION, AttributeName::IS_ACTIVE, AttributeName::PRICE
      )
      ->with(['mark:id,title,slug', 'folder:id,title,slug', 'gearbox:id,name,title']);
  }

  protected function joinSiteCategoryAssociation(int $siteId): self
  {
    $siteQuery = Site::query();
    $priceOldSiteQuery = PriceOldSite::query();
    return $this->join(TableConstants::SITES, fn(JoinClause $join) => $join->where($siteQuery->qualifyColumn(AttributeName::ID), '=', $siteId)
      ->select($siteQuery->qualifyColumn(AttributeName::CATEGORY_ASSOCIATION))
      ->leftJoin(TableConstants::PRICE_OLD_SITES, fn(JoinClause $joinClause) => $joinClause->on($siteQuery->qualifyColumn(AttributeName::ID), '=', $priceOldSiteQuery->qualifyColumn(AttributeName::SITE_ID)))
    );
  }

  public function withPriceForSite(int $siteId, array $filter = [], bool $onlyActive = true, bool $all = false, int $currentSiteId = null): self
  {
    $query = $this;

    if (($all || $onlyActive) && isset($filter['typeEnum'])) {
      $query->whereTypeEnum($filter['typeEnum']);
    }

    if (($all || $onlyActive) && isset($filter['minYear'])) {
      $query->whereYearFrom($filter['minYear']);
    }

    if (($all || $onlyActive) && isset($filter['category'])) {
      $query->whereCategory($filter['category']);
    }

    $query->join(TableConstants::OFFER_SITE, function (JoinClause $join) use ($siteId, $onlyActive, $filter, $all) {
      $join->on($this->qualifyColumn(AttributeName::ID), '=', AttributeName::OFFER_ID);

      if ($onlyActive) {
        $join->where(AttributeName::IS_ACTIVE, true);
      }

      if ($all || $onlyActive) {
        $minPriceIsset = isset($filter['minPrice']);
        if ($minPriceIsset) {
          $join->where(AttributeName::PRICE, '>=', $filter['minPrice']);
        }
      }

      $join->where(AttributeName::SITE_ID, '=', $siteId);
    });

    if ($currentSiteId) {
      $query->joinSiteCategoryAssociation($siteId);
    }
    return $query;
  }

//  public function withPriceForSite(int $siteId, array $filter = [], bool $onlyActive = true, bool $all = false, int $currentSiteId = null): self
//  {
//    return $this
//      ->when($all && isset($filter['typeEnum']) || $onlyActive && isset($filter['typeEnum']), fn($query) => $query->whereTypeEnum($filter['typeEnum']))
//      ->when($all && isset($filter['minYear']) || $onlyActive && isset($filter['minYear']), fn($query) => $query->whereYearFrom($filter['minYear']))
//      ->when($all && isset($filter['category']) || $onlyActive && isset($filter['category']), fn($query) => $query->whereCategory($filter['category']))
//      ->join(TableConstants::OFFER_SITE, function (JoinClause $join) use ($siteId, $filter, $onlyActive, $all) {
//        $join->on($this->qualifyColumn(AttributeName::ID), '=', AttributeName::OFFER_ID)
//          ->when($onlyActive, fn($query) => $query->where(AttributeName::IS_ACTIVE, true))
//          ->when($all && isset($filter['minPrice']) || $onlyActive && isset($filter['minPrice']), fn($query) => $query->where(AttributeName::PRICE, '>=', $filter['minPrice'])
//          )
//          ->where(AttributeName::SITE_ID, '=', $siteId);
//      })
//      ->when($currentSiteId, fn($query) => $query->joinSiteCategoryAssociation($siteId));
//  }
//  public function isActiveForSite(int $siteId): self
//  {
//    return $this->whereHas(TableConstants::SITES, function (Builder $builder) use($siteId){
//      $builder->where(AttributeName::IS_ACTIVE, 1)
//              ->where(AttributeName::SITE_ID, '=', $siteId);
//    });
//  }

  public function whereTypeEnum(string $typeEnum): self
  {
    return $this->where($this->qualifyColumn(AttributeName::TYPE_ENUM), $typeEnum);
  }
  public function whereTypeEnumArray(array $typeEnumArray): self
  {
    return $this->whereIn($this->qualifyColumn(AttributeName::TYPE_ENUM), $typeEnumArray);
  }
  public function whereExternalId(int $externalId): self
  {
    return $this->where($this->qualifyColumn(AttributeName::EXTERNAL_ID), '=', $externalId);
  }
  public function whereExcept(string $column, int|string $value): self
  {
    return $this->where($this->qualifyColumn($column), '!=', $value);
  }
  public function whereExceptArray(string $column, array $value): self
  {
    return $this->whereNotIn($this->qualifyColumn($column), $value);
  }
  public function whereExternalIdArray(array $externalIdArray): self
  {
    return $this->whereIn($this->qualifyColumn(AttributeName::EXTERNAL_ID), $externalIdArray);
  }

  public function whereExternalUniqueId(string $externalUniqueId): self
  {
    return $this->where($this->qualifyColumn(AttributeName::EXTERNAL_UNIQUE_ID), '=', $externalUniqueId);
  }

  public function whereMarkId(int $markId): self
  {
    return $this->where($this->qualifyColumn(AttributeName::MARK_ID), '=', $markId);
  }

  public function whereMarkIdArray(array $markIdArray): self
  {
    return $this->whereHas('mark', fn($builder) => $builder->whereIn($builder->qualifyColumn(AttributeName::ID), $markIdArray));
  }

  public function whereMarkSlug(string $markSlug): self
  {
    return $this->whereHas('mark', fn($builder) => $builder->where($builder->qualifyColumn(AttributeName::SLUG), '=', $markSlug));
  }

  public function whereMarkSlugArray(array $markSlugArray): self
  {
    $markQuery = Mark::query();
    return $this->join(TableConstants::MARKS, fn(JoinClause $joinClause) => $joinClause
      ->on($this->qualifyColumn(AttributeName::MARK_ID), "=", $markQuery->qualifyColumn(AttributeName::ID))
      ->whereIn($markQuery->qualifyColumn(AttributeName::SLUG), $markSlugArray)
    );
    //return $this->whereHas('mark', fn ($builder) => $builder->whereIn($builder->qualifyColumn(AttributeName::SLUG), $markSlugArray));
  }

  public function whereFolderId(int $folderId): self
  {
    return $this->where($this->qualifyColumn(AttributeName::FOLDER_ID), '=', $folderId);
  }

  public function whereFolderSlug(string $folderSlug): self
  {
    return $this->whereHas('folder', fn($builder) => $builder->where($builder->qualifyColumn(AttributeName::SLUG), '=', $folderSlug));
  }

  public function whereFolderSlugArray(array $folderSlugArray): self
  {
    $folderQuery = Folder::query();
    return $this->join(TableConstants::FOLDERS, fn(JoinClause $joinClause) => $joinClause
      ->on($this->qualifyColumn(AttributeName::FOLDER_ID), "=", $folderQuery->qualifyColumn(AttributeName::ID))
      ->whereIn($folderQuery->qualifyColumn(AttributeName::SLUG), $folderSlugArray)
    );
  }

  public function whereGenerationId(int $generationId): self
  {
    return $this->where($this->qualifyColumn(AttributeName::GENERATION_ID), '=', $generationId);
  }

  public function whereGenerationSlug(string $generationSlug): self
  {
    $generationQuery = Generation::query();
    return $this->join(TableConstants::GENERATIONS, fn(JoinClause $joinClause) => $joinClause
      ->on($this->qualifyColumn(AttributeName::GENERATION_ID), "=", $generationQuery->qualifyColumn(AttributeName::ID))
      ->where($generationQuery->qualifyColumn(AttributeName::SLUG), '=', $generationSlug)
    );
//    return $this->whereHas('generation', fn($builder) => $builder->where($builder->qualifyColumn(AttributeName::SLUG), '=', $generationSlug));
  }

  public function whereGenerationSlugArray(array $generationSlugArray): self
  {
    $generationQuery = Generation::query();
    return $this->join(TableConstants::GENERATIONS, fn(JoinClause $joinClause) => $joinClause
      ->on($this->qualifyColumn(AttributeName::GENERATION_ID), "=", $generationQuery->qualifyColumn(AttributeName::ID))
      ->whereIn($generationQuery->qualifyColumn(AttributeName::SLUG), $generationSlugArray)
    );
  }

  public function whereGearboxId(int $gearboxId): self
  {
    return $this->where($this->qualifyColumn(AttributeName::GEARBOX_ID), $gearboxId);
  }

  public function whereGearboxIdArray(array $gearboxIdArray): self
  {
    return $this->whereIn($this->qualifyColumn(AttributeName::GEARBOX_ID), $gearboxIdArray);
  }

  public function whereBodyTypeId(int $bodyTypeId): self
  {
    return $this->where($this->qualifyColumn(AttributeName::BODY_TYPE_ID), $bodyTypeId);
  }

  public function whereBodyTypeIdArray(array $bodyTypeIdArray): self
  {
    return $this->whereIn($this->qualifyColumn(AttributeName::BODY_TYPE_ID), $bodyTypeIdArray);
  }

  public function whereDriveTypeId(int $driveTypeId): self
  {
    return $this->where($this->qualifyColumn(AttributeName::DRIVE_TYPE_ID), $driveTypeId);
  }

  public function whereDriveTypeIdArray(array $driveTypeIdArray): self
  {
    return $this->whereIn($this->qualifyColumn(AttributeName::DRIVE_TYPE_ID), $driveTypeIdArray);
  }

  public function whereEngineTypeId(int $engineTypeId): self
  {
    return $this->where($this->qualifyColumn(AttributeName::ENGINE_TYPE_ID), $engineTypeId);
  }

  public function whereEngineTypeIdArray(array $engineTypeIdArray): self
  {
    return $this->whereIn($this->qualifyColumn(AttributeName::ENGINE_TYPE_ID), $engineTypeIdArray);
  }

  public function whereOwnerId(int $ownerId): self
  {
    return $this->where($this->qualifyColumn(AttributeName::OWNER_ID), $ownerId);
  }

  public function whereOwnerIdArray(array $ownerIdArray): self
  {
    return $this->whereIn($this->qualifyColumn(AttributeName::OWNER_ID), $ownerIdArray);
  }

  public function whereDealerId(int $dealerId): self
  {
    return $this->where($this->qualifyColumn(AttributeName::DEALER_ID), '=', $dealerId);
  }

  public function whereDealerIdArray(array $dealerIdArray): self
  {
    return $this->whereIn($this->qualifyColumn(AttributeName::DEALER_ID), $dealerIdArray);
  }

  public function whereYearFrom(int $year): self
  {
    return $this->where($this->qualifyColumn(AttributeName::YEAR), '>=', $year);
  }

  public function whereCommunicationsCountFrom(int $count): self
  {
    return $this->where($this->qualifyColumn(AttributeName::COMMUNICATIONS_COUNT), '>=', $count);
  }

  public function whereYearTo(int $year): self
  {
    return $this->where($this->qualifyColumn(AttributeName::YEAR), '<=', $year);
  }

  public function whereRunFrom(int $run): self
  {
    return $this->where($this->qualifyColumn(AttributeName::RUN), '>=', $run);
  }

  public function whereRunTo(int $run): self
  {
    return $this->where($this->qualifyColumn(AttributeName::RUN), '<=', $run);
  }
  public function wherePriceFrom(int $price): self
  {
    return $this->where(AttributeName::PRICE, '>=', $price);
  }

  public function wherePriceTo(int $price): self
  {
    return $this->where(AttributeName::PRICE, '<=', $price);
  }

  public function whereCategory(string $category): self
  {
    return $this->where($this->qualifyColumn(AttributeName::CATEGORY_ENUM), '=', $category);
  }

  public function whereUpdatedAtFrom(Carbon $updated_at): self
  {
    return $this->where($this->qualifyColumn(AttributeName::UPDATED_AT), '>=', $updated_at);
  }
}
