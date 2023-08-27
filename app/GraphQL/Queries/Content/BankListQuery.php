<?php

namespace App\GraphQL\Queries\Content;

use App\Constants\Attributes\AttributeName;
use App\GraphQL\Types\Content\BankType;
use App\Helpers\CacheTags;
use App\Models\Bank;
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
class BankListQuery extends Query
{

  protected $attributes = [
    'name' => 'banks',
    'description' => 'Список банков',
  ];

  public function type(): Type
  {
    return Type::listOf(GraphQL::type(BankType::class));
  }

  // public function args(): array
  // {
  //   return [
  //     'site_id' => ['name' => 'site_id', 'type' => Type::int(), 'rules' => ['required']]
  //   ];
  // }

  // public function args(): array
  // {
  //     return [
  //         'site_id' => [
  //             'name' => 'site_id',
  //             'type' =>  Type::id(),
  //             'rules' => ['required']
  //         ],
  //     ];
  // }

  public function resolve($root, array $args, $context, ResolveInfo $info, Closure $getSelectFields)
  {
    // if (!$args['site_id']) {
    //   return null;
    // }
    // return Bank::all()->where('id', $args['site_id']);
    return Bank::query()->paginate(2, ['*'], 'page', 2);
    $fields = $getSelectFields();
    $select = $fields->getSelect();
    // if (!$args[AttributeName::SITE_ID]) {
    //   return null;
    // }
    return $select;
    // $cacheKey = CacheTags::getCacheKey($this->attributes['name'], array_merge($args, $select));
    // return Cache::tags('banks')->rememberForever($cacheKey, fn() => $this->getBanks($select));
//    return CacheTags::rememberForever('banks', $cacheKey, $this->getBanks($select));
  }

  protected function getBanks(array $select): array
  {
    $bankQuery = Bank::query()->select($select);
    return $bankQuery->whereActive()
      ->with('media')
      ->orderByRating()
      ->get()
      ->toArray();
  }
}
