<?php

namespace App\GraphQL\Types\Content;

use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use App\Constants\Attributes\AttributeName;
use App\GraphQL\Types\Images\ArticleImageItemType;
use App\GraphQL\Types\Images\ArticleImagePreviewItemType;
use App\GraphQL\Types\Images\SlideImageElementItemType;
use App\GraphQL\Types\Images\SlideImageItemType;
use App\Models\Slide;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;


final class SlideType extends GraphQLType
{
  protected string $name = 'Slide';
  protected string $model = Slide::class;

  public function fields(): array
  {
    return [
      Field::make(AttributeName::ID)
        ->type(Type::nonNull(Type::int())),

      Field::make(AttributeName::TITLE)
        ->type(Type::string()),

      Field::make(AttributeName::BODY)
        ->type(Type::string()),

      Field::make(AttributeName::LINK)
        ->type(Type::string()),


      Field::make(AttributeName::IMAGE)
        ->isNotSelectable()
        ->type(GraphQL::type(SlideImageItemType::class)),

      Field::make(AttributeName::IMAGE_ELEMENT)
        ->isNotSelectable()
        ->type(GraphQL::type(SlideImageElementItemType::class)),
    ];
  }
}
