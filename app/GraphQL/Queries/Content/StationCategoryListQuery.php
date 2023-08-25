<?php

namespace App\GraphQL\Queries\Content;

use App\Constants\Attributes\AttributeName;
use App\GraphQL\Types\Content\StationCategoryType;
use App\Models\Site;
use App\Models\StationCategory;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Collection;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;


/**
 * Class FoldersQuery
 * @package App\GraphQL\Queries
 */
class StationCategoryListQuery extends Query
{

  protected $attributes = [
    'name' => 'stationCategory',
    'description' => 'Список категорий услуг сервиса',
  ];

  public function type(): Type
  {
    return Type::listOf(GraphQL::type(StationCategoryType::class));
  }

  public function args(): array
  {
    return [
      AttributeName::SITE_ID => ['name' => AttributeName::SITE_ID, 'type' => Type::int(), 'rules' => ['required']]
    ];
  }

  public function resolve($root, array $args, $context, ResolveInfo $info, Closure $getSelectFields)
  {
    $fields = $getSelectFields();
    if (!$args[AttributeName::SITE_ID]) {
      return null;
    }
    return $this->getStations($fields, $args);
//    $cacheKey = \Str::of(json_encode($args))->pipe('md5')->prepend("filters.{$this->attributes['name']}.");
//    return CacheTags::rememberForever('stories', $cacheKey, $this->getStories($fields, $args));
  }

  protected function getStations($fields, array $args): Collection
  {
    $siteCoalesce = Site::query()
      ->getParentId($args[AttributeName::SITE_ID])
      ->first();

    if ($siteCoalesce) {
      $storyQuery = StationCategory::query()
        ->select($fields->getSelect())
        ->with($fields->getRelations())
        ->whereSiteId($siteCoalesce->id)
        ->whereActive()
        ->orderByColumn();

      return $storyQuery->get();
    } else {
      return new Collection();
    }
  }
}
