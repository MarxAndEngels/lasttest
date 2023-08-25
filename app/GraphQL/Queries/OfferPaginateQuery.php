<?php

declare(strict_types=1);


namespace App\GraphQL\Queries;

use App\Complex\GraphQL\Query;
use App\Constants\Attributes\AttributeName;
use App\GraphQL\Middleware;
use App\GraphQL\Types\OfferType;
use App\Models\Offer;
use App\Services\Filter\GetSetService;
use App\Services\Filter\ParseUrlFromGraphQL;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

final class OfferPaginateQuery extends Query
{
  protected ?array $filter = [];
  protected $attributes = [
    'name' => 'offers',
    'description' => 'Список объявлений',
  ];
  protected $middleware = [
    Middleware\ResolvePage::class
  ];

  public function type(): Type
  {
    return GraphQL::paginate(OfferType::class, 'OfferTypePagination');
  }
  public static function createArgs(): array
  {
    return [
      'external_id_array' => ['name' => 'external_id_array', 'type' => Type::listOf(Type::int())],
      'except_external_id' => ['name' => 'except_external_id', 'type' => Type::int()],
      'mark_id' => ['name' => 'mark_id', 'type' => Type::int()],
      'mark_slug' => ['name' => 'mark_slug', 'type' => Type::string()],
      'mark_slug_array' => ['name' => 'mark_slug_array', 'type' => Type::listOf(Type::string())],
      'folder_id' => ['name' => 'folder_id', 'type' => Type::int()],
      'folder_slug' => ['name' => 'folder_slug', 'type' => Type::string()],
      'folder_slug_array' => ['name' => 'folder_slug_array', 'type' => Type::listOf(Type::string())],
      'generation_id' => ['name' => 'generation_id', 'type' => Type::int()],
      'generation_slug' => ['name' => 'generation_slug', 'type' => Type::string()],
      'generation_slug_array' => ['name' => 'generation_slug_array', 'type' =>  Type::listOf(Type::string())],
      'gearbox_id' => ['name' => 'gearbox_id', 'type' => Type::int()],
      'gearbox_id_array' => ['name' => 'gearbox_id_array', 'type' => Type::listOf(Type::int())],
      'body_type_id' => ['name' => 'body_type_id', 'type' => Type::int()],
      'body_type_id_array' => ['name' => 'body_type_id_array', 'type' => Type::listOf(Type::int())],
      'drive_type_id' => ['name' => 'drive_type_id', 'type' => Type::int()],
      'drive_type_id_array' => ['name' => 'drive_type_id_array', 'type' => Type::listOf(Type::int())],
      'engine_type_id' => ['name' => 'engine_type_id', 'type' => Type::int()],
      'engine_type_id_array' => ['name' => 'engine_type_id_array', 'type' => Type::listOf(Type::int())],
      'owner_id' => ['name' => 'owner_id', 'type' => Type::int()],
      'owner_id_array' => ['name' => 'owner_id_array', 'type' => Type::listOf(Type::int())],
      'category' => ['name' => 'category', 'type' => Type::string()],
      'url' => ['name' => 'url', 'type' => Type::string()],
      'year_from' => ['name' => 'year_from', 'type' => Type::int()],
      'year_to' => ['name' => 'year_to', 'type' => Type::int()],
      'price_from' => ['name' => 'price_from', 'type' => Type::int()],
      'price_to' => ['name' => 'price_to', 'type' => Type::int()],
      'run_from' => ['name' => 'run_from', 'type' => Type::int()],
      'run_to' => ['name' => 'run_to', 'type' => Type::int()],
      'page' => ['name' => 'page', 'type' => Type::int()],
      'limit' => ['name' => 'limit', 'type' => Type::int()],
      AttributeName::SITE_ID => ['name' => AttributeName::SITE_ID, 'type' => Type::int(), 'rules' => ['required']],
      'sort' => ['name' => 'sort', 'type' => Type::string()],
      'set' => ['name' => 'set', 'type' => Type::string()],
      'type_enum_array' => ['name' => 'set', 'type' => Type::listOf(Type::string())]
    ];
  }
  public function args(): array
  {
    return $this->createArgs();
  }

  public function resolve($root, array $args, $context, ResolveInfo $info, Closure $getSelectFields)
  {
    if (!isset($args[AttributeName::SITE_ID])) {
      return null;
    }
    if (!isset($args['page'])) {
      $args['page'] = 1;
    }
    $args[AttributeName::SITE_ID] = [
      'id' => $args[AttributeName::SITE_ID],
      'onlyActive' => true
    ];
    if(!isset($args['sort'])){
      $args['sort'] = AttributeName::PRICE.'|asc';
    }
    $fields = $getSelectFields();
    $select = $fields->getSelect();
    array_push($select, AttributeName::PRICE, AttributeName::PRICE_OLD, AttributeName::IS_ACTIVE, AttributeName::CATEGORY_ASSOCIATION, AttributeName::TYPE_ENUM, AttributeName::LOGIC, AttributeName::COMMUNICATIONS_COUNT);
    $offerQuery = Offer::query()->select($select)->with($fields->getRelations());
    if(isset($args['set']) && $args['set']){
      $filter = (new GetSetService($args['set']))->getSetFilter();
      $this->filter = $filter['filter'] ?? null;
      if(!$this->filter) {
        return $this->notFound();
      }
    }

    if(isset($args['url']) && $args['url']) {
      $this->filter = (new ParseUrlFromGraphQL($args['url'], $args[AttributeName::SITE_ID]['id']))->getFilter();
      if(!$this->filter) {
        return $this->notFound();
      }
    }
    if($this->filter) {
      $args = array_merge($args, $this->filter);
    }
    return $offerQuery->filter($args)->paginateFilter($this->getItemsLimit($args), ['*'], 'page', $args['page']);
  }
  public static function buildFilterRequest(array $args): array
  {
    return [
      AttributeName::SITE_ID => $args[AttributeName::SITE_ID],
    ];
  }

  private function getItemsLimit($args): int
  {
    $default = 20;
    $limit = $args['limit'] ?? $default;
    return (int)$limit;
  }
}
