<?php

namespace App\GraphQL\Queries\Digest;

use App\Complex\GraphQL\Query;
use App\Constants\Attributes\AttributeName;
use App\GraphQL\Types\Digest\SeoTagType;
use App\Helpers\CacheTags;
use App\Models\Site;
use App\Services\Filter\ParseUrlFromGraphQL;
use App\Services\GetSeoTagsService;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Cache;
use Rebing\GraphQL\Support\Facades\GraphQL;

final class SeoTagQuery extends Query
{
  protected array $seoTagEmpty = [
      'page_title' => null,
      'title' => null,
      'description' => null,
      'crumbs' => null
    ];
  protected ?int $siteId;
  public function args(): array
  {
    return [
      AttributeName::SITE_ID => ['name' => AttributeName::SITE_ID, 'type' => Type::int(), 'rules' => ['required']],
      AttributeName::URL => ['name' => AttributeName::URL, 'type' => Type::string(), 'rules' => ['required']],
    ];
  }

  protected $attributes = [
    'name' => 'seoTag',
    'description' => 'SEO теги',
  ];

  public function type(): Type
  {
    return GraphQL::type(SeoTagType::class);
  }

  public function resolve($root, array $args, $context, ResolveInfo $info, Closure $getSelectFields)
  {
    $site = Site::query();
    $siteCoalesce = $site->getParentId($args[AttributeName::SITE_ID])->first();
    if(!$siteCoalesce){
      return $this->seoTagEmpty;
    }
    $this->siteId = $siteCoalesce->id;
    $cacheKey = CacheTags::getCacheKey($this->attributes['name'], $args);
    return Cache::tags(["seoTag", "seoTag.{$this->siteId}"])->rememberForever($cacheKey, fn() => $this->getSeoTag($args));
//    return CacheTags::rememberForever(["seoTag", "seoTag.{$this->siteId}"], $cacheKey, $this->getSeoTag($args));
  }
  private function getSeoTag(array $args) :array
  {
    $models = (new ParseUrlFromGraphQL($args[AttributeName::URL], $this->siteId))->getModels();
    if ($models) {
      $seoTags = (new GetSeoTagsService($this->siteId, $models))->getSeoTags();
      return $seoTags ?? $this->seoTagEmpty;
    } else {
      return $this->seoTagEmpty;
    }
  }
}
