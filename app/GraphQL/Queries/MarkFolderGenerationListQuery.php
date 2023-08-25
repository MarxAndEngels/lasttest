<?php

namespace App\GraphQL\Queries;

use App\Constants\Attributes\AttributeName;
use App\Constants\Enums\OfferEnum;
use App\Constants\TableConstants;
use App\GraphQL\Types\MarkFolderGeneration\MarkFolderType;
use App\GraphQL\Types\MarkType;
use App\Helpers\CacheTags;
use App\Models\Mark;
use App\Models\Site;
use App\QueryBuilders\OfferQueryBuilder;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

final class MarkFolderGenerationListQuery extends Query
{

  protected $attributes = [
    'name' => 'markFolderGeneration',
    'description' => 'Список марок',
  ];

  public function type(): Type
  {
    return Type::listOf(GraphQL::type(MarkFolderType::class));
  }

  public function args(): array
  {
    return [
      AttributeName::SITE_ID => ['name' => AttributeName::SITE_ID, 'type' => Type::int(), 'rules' => ['required']],
      'category' => ['name' => 'category', 'type' => Type::string()]
    ];
  }

  public function resolve($root, array $args, $context, ResolveInfo $info, Closure $getSelectFields)
  {
    if (!isset($args[AttributeName::SITE_ID])) {
      return null;
    }
    $fields = $getSelectFields();
    $select = $fields->getSelect();
    $siteId = $args[AttributeName::SITE_ID];
    $currentSiteQuery = Site::query()->select(AttributeName::ID, AttributeName::FILTER, AttributeName::CATEGORY_ASSOCIATION);
    $currentSiteModel = $currentSiteQuery->whereId($siteId)->first()->toArray();
    $siteModel = $currentSiteQuery->getParentId($siteId)->first();
    $cacheKey = CacheTags::getCacheKey($this->attributes['name'], $args);
    return Cache::tags([$siteModel->id, 'filters', 'markFolderGeneration'])->rememberForever($cacheKey, fn() => $this->getMarkFolderGeneration($args, $select, $currentSiteModel, $siteModel));
//    return CacheTags::rememberForever([$siteModel->id, 'filters', 'markFolderGeneration'], $cacheKey, $this->getMarkFolderGeneration($args, $select, $currentSiteModel, $siteModel));
  }
  protected function joinOfferSite(Builder|HasMany $builder, int $siteId, $filter = null): Builder|HasMany
  {
    return $builder->join(TableConstants::OFFER_SITE, fn(JoinClause $join) =>
      $join->on(TableConstants::OFFERS.'.'.AttributeName::ID, '=',TableConstants::OFFER_SITE.'.'.AttributeName::OFFER_ID)
        ->where(TableConstants::OFFER_SITE.'.'.AttributeName::IS_ACTIVE, '=', true)
        ->where(TableConstants::OFFER_SITE.'.'.AttributeName::SITE_ID, '=', $siteId)
        ->when(isset($filter['minPrice']) && $filter['minPrice'], fn() => $join->where(TableConstants::OFFER_SITE.'.'.AttributeName::PRICE, '>=', $filter['minPrice']))
        ->when(isset($filter['category']) && $filter['category'], fn() => $join->where(TableConstants::OFFERS.'.'.AttributeName::CATEGORY_ENUM, '=', $filter['category']))
        ->when(isset($filter['minYear']) && $filter['minYear'], fn() => $join->where(TableConstants::OFFERS.'.'.AttributeName::YEAR, '>=', $filter['minYear']))
        ->when(isset($filter['typeEnum']) && $filter['typeEnum'], fn() => $join->where(TableConstants::OFFERS.'.'.AttributeName::TYPE_ENUM, '=', $filter['typeEnum']))
    );
  }
  protected function getMarkFolderGeneration(array $args, array $select, array $currentSiteModel, Site $siteModel): ?array
  {

    $filter = $currentSiteModel[AttributeName::FILTER];

    if (isset($args['category']) && $args['category']) {
      $filter['category'] = $this->getCategoryFromValue($args['category'], $currentSiteModel[AttributeName::CATEGORY_ASSOCIATION]) ?: \Str::upper($args['category']);
    }
    $siteId = $siteModel->id;
    $markQuery = Mark::query();

    return
      $markQuery
        ->leftJoin(TableConstants::FOLDERS, 'folders.mark_id', '=', 'marks.id')
        ->leftJoin(TableConstants::GENERATIONS, 'generations.folder_id', '=', 'folders.id')
        ->leftJoin(TableConstants::OFFERS, 'offers.folder_id', '=', 'folders.id')
        ->select('marks.*', DB::raw('COUNT(DISTINCT offers.id) as offers_count'))
        ->tap(fn() => $this->joinOfferSite($markQuery, $siteId, $filter))
        ->groupBy('marks.id')
        ->orderBy(AttributeName::TITLE)
        ->with(['folders' => fn(HasMany $folderBuilder) =>
          $folderBuilder
            ->leftJoin(TableConstants::OFFERS, 'offers.folder_id', '=', 'folders.id')
            ->select('folders.*', DB::raw('COUNT(DISTINCT offers.id) as offers_count'))
            ->tap(fn() => $this->joinOfferSite($folderBuilder, $siteId, $filter))
            ->with(['generations' => fn(HasMany $generationBuilder) =>
              $generationBuilder->leftJoin(TableConstants::OFFERS, 'offers.generation_id', '=', 'generations.id')
                ->select('generations.*', DB::raw('COUNT(DISTINCT offers.id) as offers_count'))
                ->tap(fn() => $this->joinOfferSite($generationBuilder, $siteId, $filter))
                ->groupBy('generations.id')
            ])
            ->groupBy('folders.id')
        ])
        ->get()
        ->toArray();



    $markQuery->withCount([
        TableConstants::OFFERS => fn(OfferQueryBuilder $builder) =>
        $builder->withPriceForSite($siteModel->id, $filter)
      ])
      ->with('folders', fn($builder) =>
      $builder->withCount([
        TableConstants::OFFERS => fn(OfferQueryBuilder $offerBuilder) =>
        $offerBuilder->withPriceForSite($siteModel->id, $filter)
      ])
        ->whereHas(TableConstants::OFFERS, fn(OfferQueryBuilder $offerBuilder) => $offerBuilder
          ->withPriceForSite($siteModel->id, $filter)))
      ->whereHas(TableConstants::OFFERS, fn(OfferQueryBuilder $builder) => $builder
        ->withPriceForSite($siteModel->id, $filter))
      ->orderBy(AttributeName::TITLE);
    return dd($markQuery->toSql());
  }

  private function getCategoryFromValue(string $category, array $categoryAssociation): string
  {
    return array_search($category, $categoryAssociation, true);
  }
}
