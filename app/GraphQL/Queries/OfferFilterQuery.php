<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Complex\GraphQL\Query;
use App\Constants\Attributes\AttributeName;
use App\GraphQL\Types\OfferFilterValuesType;
use App\Models\Offer;
use App\Services\Filter\GetSetService;
use App\Services\Filter\ParseUrlFromGraphQL;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

final class OfferFilterQuery extends Query
{
  protected ?array $filter = [];
  protected $attributes = [
    'name' => 'offerFilters',
    'description' => 'Фильтр объявлений',
  ];

  private array $availableKeys = [
    'mark',
    'folder',
    'generation',
    'gearbox',
    'engineType',
    'driveType',
    'bodyType',
    'owner',
    'year',
    'price',
    'run',
    'chosen'
  ];
  public function args(): array
  {
    return OfferPaginateQuery::createArgs();
  }

  public function type(): Type
  {
    return GraphQL::type(OfferFilterValuesType::class);
  }

  public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
  {
    $select =  collect($resolveInfo->getFieldSelection())->keys()->all();
    $args[AttributeName::SITE_ID] = [
      'id' => $args[AttributeName::SITE_ID],
      'onlyActive' => true
    ];
    if(isset($args[AttributeName::SET]) && $args[AttributeName::SET]){
      $filter = (new GetSetService($args[AttributeName::SET]))->getSetFilter();
      $this->filter = $filter['filter'] ?? null;
      if(!$this->filter) {
        #\Log::info("Filter GetSetService 404 / url: {$args[AttributeName::URL]} site-id: {$args[AttributeName::SITE_ID]['id']}");
        return $this->notFound();
      }
    }
    if(isset($args[AttributeName::URL]) && $args[AttributeName::URL]) {
      $this->filter = (new ParseUrlFromGraphQL($args[AttributeName::URL], $args[AttributeName::SITE_ID]['id']))->getFilter();
      if(!$this->filter) {
        #\Log::info("Filter 404 / url: {$args[AttributeName::URL]} site-id: {$args[AttributeName::SITE_ID]['id']}");
        return $this->notFound();
      }
    }
    if($this->filter) {
      $args = array_merge($args, $this->filter);
    }
    return Offer::query()->getCountableFilter(
      collect($this->availableKeys)->intersect($select)->all(),
      $args,
    );
  }
}
