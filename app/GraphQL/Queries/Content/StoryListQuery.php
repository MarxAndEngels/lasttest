<?php

namespace App\GraphQL\Queries\Content;

use App\Constants\Attributes\AttributeName;
use App\GraphQL\Types\Content\StoryType;
use App\Models\Site;
use App\Models\Story;
use App\Services\GetWeekSaleService;
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
class StoryListQuery extends Query
{

  protected $attributes = [
    'name' => 'stories',
    'description' => 'Список историй',
  ];

  public function type(): Type
  {
    return Type::listOf(GraphQL::type(StoryType::class));
  }

  public function args(): array
  {
    return [
      AttributeName::SITE_ID => ['name' => AttributeName::SITE_ID, 'type' => Type::int(), 'rules' => ['required']]
    ];
  }

  public function resolve($root, array $args, $context, ResolveInfo $info, Closure $getSelectFields, GetWeekSaleService $weekSaleService)
  {
    $fields = $getSelectFields();
    if (!$args[AttributeName::SITE_ID]) {
      return null;
    }
    return $this->getStories($fields, $args, $weekSaleService->getExcludeWeekSales());
//    $cacheKey = \Str::of(json_encode($args))->pipe('md5')->prepend("filters.{$this->attributes['name']}.");
//    return CacheTags::rememberForever('stories', $cacheKey, $this->getStories($fields, $args));
  }

  protected function getStories($fields, array $args, array $weekSales = []): ?Collection
  {
    $siteCoalesce = Site::query()
      ->getParentId($args[AttributeName::SITE_ID])
      ->first();

    if (!$siteCoalesce) {
      return new Collection();
    }

    $storyQuery = Story::query()
      ->select($fields->getSelect())
      ->with($fields->getRelations())
      ->whereSiteId($siteCoalesce->id)
      ->whereActive()
      ->orderByColumn();

    if ($weekSales) {
      $storyQuery->whereNotTitleArray($weekSales);
    }

    return $storyQuery->get();
  }
}
