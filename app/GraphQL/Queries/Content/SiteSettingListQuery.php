<?php

namespace App\GraphQL\Queries\Content;

use App\Constants\Attributes\AttributeName;
use App\GraphQL\Types\Content\SiteSettingType;
use App\Helpers\CacheTags;
use App\Models\SiteSetting;
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
class SiteSettingListQuery extends Query
{

  protected $attributes = [
    'name' => 'settings',
    'description' => 'Настройки сайта',
  ];

  public function type(): Type
  {
    return GraphQL::type(SiteSettingType::class);
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
    $cacheKey = CacheTags::getCacheKey($this->attributes['name'], array_merge($args, $select));
    return Cache::tags([$this->attributes['name'], "siteSetting.{$args[AttributeName::SITE_ID]}"])->rememberForever($cacheKey, fn() => $this->getSiteSetting($args[AttributeName::SITE_ID]));
//    return CacheTags::rememberForever([$this->attributes['name'], "siteSetting.{$args[AttributeName::SITE_ID]}"], $cacheKey, $this->getSiteSetting($args[AttributeName::SITE_ID]));
  }

  protected function getSiteSetting(int $siteId): array
  {
    $siteSettingQuery = SiteSetting::query();
    return $siteSettingQuery->where(AttributeName::SITE_ID, '=', $siteId)
      ->first()
      ->toArray();
  }
}
