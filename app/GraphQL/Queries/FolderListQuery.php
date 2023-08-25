<?php

namespace App\GraphQL\Queries;

use App\Constants\Attributes\AttributeName;
use App\Constants\TableConstants;
use App\GraphQL\Types\FolderType;
use App\Helpers\CacheTags;
use App\Models\Folder;
use App\Models\Mark;
use App\Models\Site;
use App\QueryBuilders\OfferQueryBuilder;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Cache;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;


/**
 * Class FoldersQuery
 * @package App\GraphQL\Queries
 */
class FolderListQuery extends Query
{

  protected $attributes = [
    'name' => 'folders',
    'description' => 'Список моделей',
  ];

  public function type(): Type
  {
    return Type::listOf(GraphQL::type(FolderType::class));
  }

  public function args(): array
  {
    return [
      AttributeName::MARK_ID => ['name' => AttributeName::MARK_ID, 'type' => Type::int()],
      AttributeName::MARK_SLUG => ['name' => AttributeName::MARK_SLUG, 'type' => Type::string()],
      AttributeName::SITE_ID => ['name' => AttributeName::SITE_ID, 'type' => Type::int(), 'rules' => ['required']],
      AttributeName::CATEGORY => ['name' => AttributeName::CATEGORY, 'type' => Type::string()]
    ];
  }

  public function resolve($root, array $args, $context, ResolveInfo $info, Closure $getSelectFields)
  {
    if (!$args[AttributeName::SITE_ID]) {
      return null;
    }

    $fields = $getSelectFields();
    $select = $fields->getSelect();
    $cacheKey = CacheTags::getCacheKey($this->attributes['name'], $args);
    return Cache::tags([$args[AttributeName::SITE_ID], 'filters', 'folders'])->rememberForever($cacheKey, fn() => $this->getFolders($args, $select));
//    return CacheTags::rememberForever([$args[AttributeName::SITE_ID], 'filters', 'folders'], $cacheKey, $this->getFolders($args, $select));
  }
  protected function getFolders(array $args, array $select): ?array
  {
    $folderQuery = Folder::query()->select($select);

    if(isset($args[AttributeName::MARK_ID])){
      $folderQuery->where(AttributeName::MARK_ID, $args[AttributeName::MARK_ID]);
    }
    if(isset($args[AttributeName::MARK_SLUG])){
      $markQuery = Mark::query();
      $folderQuery->whereHas('mark', fn($builder) => $builder->where($markQuery->qualifyColumn(AttributeName::SLUG), '=', $args[AttributeName::MARK_SLUG]));
    }
    $siteId = $args[AttributeName::SITE_ID];

    $currentSiteQuery = Site::query()->select(AttributeName::ID, AttributeName::FILTER, AttributeName::CATEGORY_ASSOCIATION);
    $currentSiteModel = $currentSiteQuery->whereId($siteId)->first()->toArray();
    $filter = $currentSiteModel[AttributeName::FILTER];

    $siteModel = $currentSiteQuery->getParentId($siteId)->first();

    if (isset($args[AttributeName::CATEGORY]) && $args[AttributeName::CATEGORY]) {
      $filter[AttributeName::CATEGORY] = $this->getCategoryFromValue($args[AttributeName::CATEGORY], $currentSiteModel[AttributeName::CATEGORY_ASSOCIATION]);
    }
    $folderQuery->withCount(
      [
        TableConstants::OFFERS => fn(OfferQueryBuilder $builder) =>
        $builder->withPriceForSite($siteModel->id, $filter)
      ]
    )->whereHas(TableConstants::OFFERS, fn (OfferQueryBuilder $builder) => $builder
      ->withPriceForSite($siteModel->id, $filter)
    )
      ->orderBy($folderQuery->qualifyColumn(AttributeName::TITLE));
    return $folderQuery->get()->toArray();
  }

  private function getCategoryFromValue(string $category, array $categoryAssociation): string
  {
    return array_search($category, $categoryAssociation, true);
  }
}
