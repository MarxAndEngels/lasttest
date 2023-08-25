<?php

declare(strict_types=1);

namespace App\GraphQL\UnionTypes;

use App\GraphQL\Types\OfferType;
use App\GraphQL\Types\ParseUrlTypes\OfferUrlFilterPaginationType;
use App\GraphQL\Types\ParseUrlTypes\OfferUrlType;
use App\Models\Offer;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\UnionType;

final class OfferUrlUnionType extends UnionType
{
  protected $attributes = [
    'name' => 'OfferUnionType',
  ];

  public function types(): array
  {
    return [
      GraphQL::type(OfferUrlType::class),
      GraphQL::type(OfferUrlFilterPaginationType::class),
    ];
  }

  public function resolveType(array $filter)
  {
    if(isset($filter['external_id']))
    {
      return GraphQL::type(OfferUrlType::class);
    }else {
      return GraphQL::type(OfferUrlFilterPaginationType::class);
    }
  }
}
