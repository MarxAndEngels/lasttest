<?php

namespace App\GraphQL\Queries\Content;

use App\Complex\GraphQL\Query;
use App\Constants\Attributes\AttributeName;
use App\GraphQL\Types\Content\ArticleType;
use App\Models\Article;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;


/**
 * Class FoldersQuery
 * @package App\GraphQL\Queries
 */
class ArticleQuery extends Query
{

  protected $attributes = [
    'name' => 'article',
    'description' => 'Статья',
  ];

  public function type(): Type
  {
    return GraphQL::type(ArticleType::class);
  }

  public function args(): array
  {
    return [
      AttributeName::SITE_ID => ['name' => AttributeName::SITE_ID, 'type' => Type::int(), 'rules' => ['required']],
      AttributeName::URL => ['name' => AttributeName::URL, 'type' => Type::string(), 'rules' => ['required']],
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
    $article = $this->getArticle($select, $relations, $args[AttributeName::URL]);
    if($article){
      $article->views = $article->views + 1;
      $article->save();
      return $article->toArray();
    }
    return $this->notFound();
  }

  protected function getArticle(array $select, array $relations, string $url): ?Article
  {
    $articleQuery = Article::query()->select($select)->with($relations);

    return $articleQuery->whereActive()
                        ->whereUrl($url)
                        ->first();
  }
}
