<?php

namespace App\GraphQL\Queries\Content;

use App\Constants\Attributes\AttributeName;
use App\GraphQL\Types\Content\ArticleType;
use App\Helpers\CacheTags;
use App\Models\Article;
use App\Models\ArticleCategory;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Pagination\LengthAwarePaginator;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Complex\GraphQL\Query;


/**
 * Class FoldersQuery
 * @package App\GraphQL\Queries
 */
class ArticleListPaginateQuery extends Query
{

  protected $attributes = [
    'name' => 'articlesPaginate',
    'description' => 'Список статей',
  ];

  public function type(): Type
  {
    return GraphQL::paginate(ArticleType::class, 'ArticlesPaginate');
  }

  public function args(): array
  {
    return [
      AttributeName::SITE_ID => ['name' => AttributeName::SITE_ID, 'type' => Type::int(), 'rules' => ['required']],
      'category_url' => ['name' => 'category_url', 'type' => Type::string(), 'rules' => ['required']],
      'page' => ['name' => 'page', 'type' => Type::int(), 'default' => 1],
      'limit' => ['name' => 'limit', 'type' => Type::int(), 'default' => 20],
    ];
  }

  public function resolve($root, array $args, $context, ResolveInfo $info, Closure $getSelectFields)
  {
    $fields = $getSelectFields();
    $select = $fields->getSelect();
    if (!$args[AttributeName::SITE_ID]) {
      return null;
    }
    if (!isset($args['page'])) {
      $args['page'] = 1;
    }

    $relations = $fields->getRelations();
    $articles = $this->getArticle($select, $relations, $args);
    return $articles ?: $this->notFound();
  }

  protected function getArticle(array $select, array $relations, array $args): ?LengthAwarePaginator
  {
    $articleQuery = Article::query()->select($select)->with($relations);

    return $articleQuery->whereCategoryUrl($args['category_url'])->whereActive()->latest(AttributeName::PUBLISHED_AT)->paginate($this->getItemsLimit($args), ['*'], 'page', $args['page']);
  }

  protected function getItemsLimit($args): int
  {
    $default = 20;
    $limit = $args['limit'] ?? $default;
    return (int)$limit;
  }
}
