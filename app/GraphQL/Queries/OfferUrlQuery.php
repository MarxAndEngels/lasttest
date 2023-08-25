<?php

namespace App\GraphQL\Queries;

use App\Complex\GraphQL\Query;
use App\Constants\Attributes\AttributeName;
use App\GraphQL\UnionTypes\OfferUrlUnionType;
use App\Services\Filter\ParseUrlFromGraphQL;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

final class OfferUrlQuery extends Query
{
  public function args(): array
  {
    return [
      AttributeName::SITE_ID => ['name' => AttributeName::SITE_ID, 'type' => Type::int(), 'rules' => ['required']],
      AttributeName::URL => ['name' => AttributeName::URL, 'type' => Type::string(), 'rules' => ['required']],
    ];
  }
  protected $attributes = [
    'name' => 'offerUrl',
    'description' => 'Определение объявление или фильтр',
  ];
  public function type(): Type
  {
    return GraphQL::type(OfferUrlUnionType::class);
  }
  public function resolve($root, array $args, $context, ResolveInfo $info, Closure $getSelectFields)
  {
    if (!isset($args[AttributeName::SITE_ID])) {
      return null;
    }
    $filter = (new ParseUrlFromGraphQL($args[AttributeName::URL], $args[AttributeName::SITE_ID]))->getFilter();
    if(!$filter){
      #\Log::info("OfferUrl 404 / url: {$args[AttributeName::URL]} site-id: {$args[AttributeName::SITE_ID]}");
      return $this->notFound();
    }
    return $filter;
  }

}
