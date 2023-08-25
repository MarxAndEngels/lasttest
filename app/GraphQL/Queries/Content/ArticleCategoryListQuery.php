<?php

namespace App\GraphQL\Queries\Content;

use App\Constants\Attributes\AttributeName;
use App\GraphQL\Types\Content\ArticleCategoryType;
use App\Models\ArticleCategory;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;


/**
 * Class FoldersQuery
 * @package App\GraphQL\Queries
 */
class ArticleCategoryListQuery extends Query
{

  protected $attributes = [
    'name' => 'articleCategory',
    'description' => 'Категории статей',
  ];

  public function type(): Type
  {
    return Type::listOf(GraphQL::type(ArticleCategoryType::class));
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
    $relations = $fields->getRelations();
    return $this->getArticleCategories($select, $relations);
  }

  protected function getArticleCategories(array $select, array $relations): array
  {
    $articleCategoryQuery = ArticleCategory::query()->select($select)->with($relations);

    return $articleCategoryQuery->whereActive()
      ->latest()
      ->get()
      ->toArray();
  }
}
