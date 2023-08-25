<?php

namespace App\GraphQL\Queries\Digest;

use App\Constants\Attributes\AttributeName;
use App\Constants\TableConstants;
use App\GraphQL\Types\Digest\RegionType;
use App\Models\Region;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Query\JoinClause;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

final class RegionListQuery extends Query
{

  protected $attributes = [
    'name' => 'regions',
    'description' => 'Список регионов',
  ];

  public function type(): Type
  {
    return Type::listOf(GraphQL::type(RegionType::class));
  }

  public function args(): array
  {
    return [
      AttributeName::SITE_ID => ['name' => AttributeName::SITE_ID, 'type' => Type::int()]
    ];
  }

  public function resolve($root, array $args, $context, ResolveInfo $info, Closure $getSelectFields)
  {
    $regionQuery = Region::query();
    $siteId = $args[AttributeName::SITE_ID] ?? null;

    return
      $regionQuery->leftJoin(TableConstants::REGION_SITE, function (JoinClause $join) use ($siteId, $regionQuery) {
        $join->on($regionQuery->qualifyColumn(AttributeName::ID), '=', AttributeName::REGION_ID)
          ->where(AttributeName::SITE_ID, '=', $siteId);
        })
      ->select([
        $regionQuery->qualifyColumn('*'),
        \DB::raw('coalesce(' . TableConstants::REGION_SITE . '.' . AttributeName::ORDER_COLUMN . ', ' . TableConstants::REGIONS . '.' . AttributeName::ORDER_COLUMN . ') AS order_column_site'),
      ])
      ->orderBy('order_column_site')
      ->orderBy(AttributeName::TITLE)
      ->get();
  }

}
