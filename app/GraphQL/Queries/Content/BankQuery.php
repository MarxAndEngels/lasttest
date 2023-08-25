<?php

namespace App\GraphQL\Queries\Content;

use App\Complex\GraphQL\Query;
use App\Constants\Attributes\AttributeName;
use App\GraphQL\Types\Content\BankType;
use App\Helpers\CacheTags;
use App\Models\Bank;
use App\Models\Site;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Cache;
use Rebing\GraphQL\Support\Facades\GraphQL;


/**
 * Class FoldersQuery
 * @package App\GraphQL\Queries
 */
class BankQuery extends Query
{

  protected $attributes = [
    'name' => 'bank',
    'description' => 'Банк',
  ];

  public function type(): Type
  {
    return GraphQL::type(BankType::class);
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
    $cacheKey = CacheTags::getCacheKey($args[AttributeName::SLUG], array_merge($args, $select), 'bank');
    $bank = Cache::tags(['bank', "bank.{$args[AttributeName::SLUG]}"])->rememberForever($cacheKey, fn() => $this->getBank($select, $args[AttributeName::SLUG], $args[AttributeName::SITE_ID]));
//    $bank = CacheTags::rememberForever(['bank', "bank.{$args[AttributeName::SLUG]}"], $cacheKey, $this->getBank($select, $args[AttributeName::SLUG], $args[AttributeName::SITE_ID]));
    return $bank ?: $this->notFound();
  }
  protected function getBank(array $select, string $slug, int $siteId): array
  {
    $site = Site::query();
    $siteCoalesce = $site->getParentId($siteId)->first();
    if($siteCoalesce){
      $bankQuery = Bank::query()->select($select);
      $bank = $bankQuery->whereSlug($slug)->whereActive()->withText($siteCoalesce->id)->first();
      return $bank ? $bank->toArray() : [];
    }else{
      return [];
    }
  }
}
