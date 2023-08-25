<?php

namespace App\GraphQL\Queries\Digest;

use App\Complex\GraphQL\Query;
use App\Constants\Attributes\AttributeName;
use App\GraphQL\Types\Digest\DealerType;
use App\Helpers\CacheTags;
use App\Models\Dealer;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Cache;
use Rebing\GraphQL\Support\Facades\GraphQL;


/**
 * Class FoldersQuery
 * @package App\GraphQL\Queries
 */
class DealerQuery extends Query
{

  protected $attributes = [
    'name' => 'dealer',
    'description' => 'Автосалон',
  ];

  public function type(): Type
  {
    return GraphQL::type(DealerType::class);
  }

  public function args(): array
  {
    return [
      AttributeName::SITE_ID => ['name' => AttributeName::SITE_ID, 'type' => Type::int(), 'rules' => ['required']],
      AttributeName::SLUG => ['name' => AttributeName::SLUG, 'type' => Type::string(), 'rules' => ['required']],
    ];
  }

  public function resolve($root, array $args, $context, ResolveInfo $info, Closure $getSelectFields)
  {
    $fields = $getSelectFields();
    $select = $fields->getSelect();
    if (!$args[AttributeName::SITE_ID]) {
      return null;
    }
    if (!$args[AttributeName::SLUG]) {
      return null;
    }
    $cacheKey = CacheTags::getCacheKey($this->attributes['name'], array_merge($args, $select));

    $dealer = Cache::tags("dealer.{$args[AttributeName::SLUG]}")->rememberForever($cacheKey, fn() => $this->getDealer($select, $args[AttributeName::SITE_ID], $args[AttributeName::SLUG]));
//    $dealer = CacheTags::rememberForever("dealer.{$args[AttributeName::SLUG]}", $cacheKey, $this->getDealer($select, $args[AttributeName::SITE_ID], $args[AttributeName::SLUG]));
    return $dealer ?: $this->notFound();
  }

  public function getDealer(array $select, int $siteId, string $slug): ?array
  {
    $dealerQuery = Dealer::query()->select($select);
    return $dealerQuery
      ->where(AttributeName::SLUG, '=', $slug)
      ->whereHas('sites', fn($query) => $query->where(AttributeName::SITE_ID, '=', $siteId))->first()?->toArray();
  }
}
