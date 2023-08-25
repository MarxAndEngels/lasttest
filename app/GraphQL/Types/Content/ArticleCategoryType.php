<?php

namespace App\GraphQL\Types\Content;


use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use App\Constants\Attributes\AttributeName;
use App\Models\ArticleCategory;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Rebing\GraphQL\Support\Facades\GraphQL;

final class ArticleCategoryType extends GraphQLType
{
  protected string $name = 'ArticleCategory';
  protected string $model = ArticleCategory::class;

  public function fields(): array
  {
    return [
      Field::make(AttributeName::ID)
        ->type(Type::nonNull(Type::int())),

      Field::make(AttributeName::PAGE_TITLE)
        ->type(Type::string()),

      Field::make(AttributeName::LONG_TITLE)
        ->type(Type::string()),

      Field::make(AttributeName::DESCRIPTION)
        ->type(Type::string()),

      Field::make(AttributeName::SLUG)
        ->type(Type::string()),

      Field::make(AttributeName::URL)
        ->type(Type::string()),

      Field::make('articles')
        ->args(['limit' => ['type' => Type::int(), 'default' => 3]])
        ->query(fn(array $args, HasMany $query) =>
                  $query->with('media')
                    ->where($query->qualifyColumn(AttributeName::IS_ACTIVE), 1)
                    ->where($query->qualifyColumn(AttributeName::PUBLISHED_AT), '<=', Carbon::now())
                    ->latest(AttributeName::PUBLISHED_AT)->limit($args['limit']))
        ->type(Type::listOf(GraphQL::type(ArticleType::class))),

    ];
  }
}
