<?php

namespace App\GraphQL\Queries\Digest;

use App\Constants\Attributes\AttributeName;
use App\GraphQL\Types\Digest\DealerType;
use App\Helpers\CacheTags;
use App\Models\Dealer;
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
class DealerListQuery extends Query
{

  protected $attributes = [
    'name' => 'dealers',
    'description' => 'Список автосалонов',
  ];

  public function type(): Type
  {
    return Type::listOf(GraphQL::type(DealerType::class));
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
    $select = $fields->getSelect();
    if (!$args[AttributeName::SITE_ID]) {
      return null;
    }
    $cacheKey = CacheTags::getCacheKey($this->attributes['name'], array_merge($select, $args));
    return Cache::tags(['filters', 'dealers'])->rememberForever($cacheKey, fn() => $this->getDealers($select, $args[AttributeName::SITE_ID]));
//    return CacheTags::rememberForever(['filters', 'dealers'], $cacheKey, $this->getDealers($select, $args[AttributeName::SITE_ID]));

  }

  public function getDealers(array $select, int $siteId): array
  {
    $dealerQuery = Dealer::query()->select($select);
    return $dealerQuery->whereHas('sites',  fn($query) => $query->where(AttributeName::SITE_ID, '=', $siteId))
      ->orderBy($dealerQuery->qualifyColumn(AttributeName::RATING), 'DESC')->get()->toArray();
  }
}
