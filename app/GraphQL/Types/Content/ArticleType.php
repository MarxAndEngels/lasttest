<?php

namespace App\GraphQL\Types\Content;


use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use App\Constants\Attributes\AttributeName;
use App\GraphQL\Fields\FormatDate;
use App\GraphQL\Types\Images\ArticleImageItemType;
use App\GraphQL\Types\Images\ArticleImagePreviewItemType;
use App\GraphQL\Types\Images\ArticleImageSlideItemType;
use App\Models\Article;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

final class ArticleType extends GraphQLType
{
  protected string $name = 'Article';
  protected string $model = Article::class;

  public function fields(): array
  {
    return [
      Field::make(AttributeName::ID)
        ->type(Type::nonNull(Type::int())),

      Field::make(AttributeName::PAGE_TITLE)
        ->type(Type::string()),

      Field::make(AttributeName::LONG_TITLE)
        ->type(Type::string()),

      Field::make(AttributeName::IMAGE)
        ->isNotSelectable()
        ->type(GraphQL::type(ArticleImageItemType::class)),

      Field::make(AttributeName::IMAGE_PREVIEW)
        ->isNotSelectable()
        ->type(GraphQL::type(ArticleImagePreviewItemType::class)),

      Field::make(AttributeName::IMAGE_SLIDE)
        ->isNotSelectable()
        ->type(Type::listOf(GraphQL::type(ArticleImageSlideItemType::class))),

      Field::make(AttributeName::SHORT_DESCRIPTION)
        ->type(Type::string()),

      Field::make(AttributeName::DESCRIPTION)
        ->type(Type::string()),

      Field::make(AttributeName::BODY)
        ->type(Type::string()),

      Field::make(AttributeName::SLUG)
        ->type(Type::string()),

      Field::make(AttributeName::URL)
        ->type(Type::string()),

      Field::make(AttributeName::VIEWS)
        ->type(Type::string()),

      Field::make(AttributeName::VIDEO_YOUTUBE)
        ->type(Type::string()),

      Field::make('category')
        ->alias('articleCategory')
        ->type(GraphQL::type(ArticleCategoryType::class)),

      FormatDate::make('publishedAt')->alias('published_at'),
      FormatDate::make('createdAt')->alias('created_at'),
      FormatDate::make('updatedAt')->alias('updated_at'),

    ];
  }
}
