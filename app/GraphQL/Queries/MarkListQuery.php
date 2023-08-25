<?php

namespace App\GraphQL\Queries;

use App\Constants\Attributes\AttributeName;
use App\Constants\TableConstants;
use App\GraphQL\Types\MarkType;
use App\Helpers\CacheTags;
use App\Models\Mark;
use App\Models\Site;
use App\QueryBuilders\OfferQueryBuilder;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Cache;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

final class MarkListQuery extends Query
{

  protected $attributes = [
    'name' => 'marks',
    'description' => 'Список марок',
  ];

  public function type(): Type
  {
    return Type::listOf(GraphQL::type(MarkType::class));
  }

  public function args(): array
  {
    return [
      AttributeName::ID => ['name' => AttributeName::ID, 'type' => Type::id()],
      AttributeName::SLUG => ['name' => AttributeName::SLUG, 'type' => Type::string()],
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
    return Cache::tags([$siteModel->id, 'filters', 'marks'])->rememberForever($cacheKey, fn() => $this->getMarks($args, $select, $currentSiteModel, $siteModel));
//    return CacheTags::rememberForever([$siteModel->id, 'filters', 'marks'], $cacheKey, $this->getMarks($args, $select, $currentSiteModel, $siteModel));
  }

  protected function getMarks(array $args, array $select, array $currentSiteModel, Site $siteModel): ?array
  {
    $markQuery = Mark::query();
    $select = collect($select)->map(fn($s) => $markQuery->qualifyColumn($s))->all();
    $markQuery->select($select);

    if (isset($args[AttributeName::ID])) {
      return $markQuery->where(AttributeName::ID, $args[AttributeName::ID])->first()->toArray();
    }

    if (isset($args[AttributeName::SLUG])) {
      return $markQuery->where('title', $args['title'])->first()->toArray();
    }

    $filter = $currentSiteModel[AttributeName::FILTER];

    if (isset($args['category']) && $args['category']) {
      $filter['category'] = $this->getCategoryFromValue($args['category'], $currentSiteModel[AttributeName::CATEGORY_ASSOCIATION]);
    }
    $markQuery->withCount(
      [
        TableConstants::OFFERS => fn(OfferQueryBuilder $builder) => $builder->withPriceForSite($siteModel->id, $filter)
      ]
    )
      ->whereHas(TableConstants::OFFERS, fn(OfferQueryBuilder $builder) => $builder->withPriceForSite($siteModel->id, $filter))
//      ->leftJoin(TableConstants::MARK_SITE, fn(JoinClause $join) => $join->on($markQuery->qualifyColumn(AttributeName::ID), '=', AttributeName::MARK_ID)
//        ->where(AttributeName::SITE_ID, '=', $siteId)
//      )
//      ->addSelect(
//        [
//          \DB::raw('coalesce(' . TableConstants::MARK_SITE . '.' . AttributeName::ORDER_COLUMN . ', ' . TableConstants::MARKS . '.' . AttributeName::ORDER_COLUMN . ') AS order_column_site')
//        ])
//      ->orderBy('order_column_site')
//      ->orderBy($markQuery->qualifyColumn(AttributeName::ORDER_COLUMN))
      ->orderBy(AttributeName::TITLE);
    return $markQuery->get()->toArray();
  }

  private function getCategoryFromValue(string $category, array $categoryAssociation): string
  {
    return array_search($category, $categoryAssociation, true);
  }
}
