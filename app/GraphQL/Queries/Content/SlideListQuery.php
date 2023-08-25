<?php

namespace App\GraphQL\Queries\Content;

use App\Constants\Attributes\AttributeName;
use App\GraphQL\Types\Content\SlideType;
use App\Models\Site;
use App\Models\Slide;
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
class SlideListQuery extends Query
{

  protected $attributes = [
    'name' => 'slides',
    'description' => 'Список баннеров',
  ];

  public function type(): Type
  {
    return Type::listOf(GraphQL::type(SlideType::class));
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
    return $this->getSlides($fields, $args);
//    $cacheKey = \Str::of(json_encode($args))->pipe('md5')->prepend("filters.{$this->attributes['name']}.");
//    return CacheTags::rememberForever('stories', $cacheKey, $this->getStories($fields, $args));
  }

  protected function getSlides($fields, array $args): ?Collection
  {
    $select = $fields->getSelect();
    $site = Site::query();
    $siteCoalesce = $site->getParentId($args[AttributeName::SITE_ID])->first();
    if ($siteCoalesce) {
      $storyQuery = Slide::query()->select($select)->with($fields->getRelations());
      return $storyQuery->whereSiteId($siteCoalesce->id)
        ->whereActive()
        ->orderByColumn()->get();
    } else {
      return collect([]);
    }
  }
}
