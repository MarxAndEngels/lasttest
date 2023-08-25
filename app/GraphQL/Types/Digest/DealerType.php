<?php

namespace App\GraphQL\Types\Digest;


use App\Complex\GraphQL\Field;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Complex\GraphQL\Type as GraphQLType;
use App\Constants\Attributes\AttributeName;
use App\GraphQL\Types\Images\DealerImageItemType;
use GraphQL\Type\Definition\Type;

final class DealerType extends GraphQLType
{
  protected string $name = 'Dealer';

  public function fields(): array
  {
    return [
      Field::make(AttributeName::ID)
        ->type(Type::nonNull(Type::int())),

      Field::make(AttributeName::SLUG)
        ->type(Type::string()),

      Field::make(AttributeName::TITLE)
        ->type(Type::string()),

      Field::make(AttributeName::CITY)
        ->type(Type::string()),

      Field::make(AttributeName::ADDRESS)
        ->type(Type::string()),

      Field::make(AttributeName::METRO)
        ->type(Type::string()),

      Field::make(AttributeName::SCHEDULE)
        ->type(Type::string()),

      Field::make(AttributeName::PHONE)
        ->type(Type::string()),

      Field::make(AttributeName::COORDINATES)
        ->type(Type::string()),

      Field::make(AttributeName::SITE)
        ->type(Type::string()),

      Field::make(AttributeName::RATING)
        ->type(Type::float()),

      Field::make(AttributeName::YOUTUBE_PLAYLIST_REVIEW)
        ->type(Type::string()),

      Field::make(AttributeName::SHORT_DESCRIPTION)
        ->type(Type::string()),

      Field::make(AttributeName::DESCRIPTION)
        ->type(Type::string()),

      Field::make(AttributeName::IMAGE_LOGO)
        ->isNotSelectable()
        ->type(Type::string()),

      Field::make(AttributeName::IMAGES)
        ->type(Type::listOf(GraphQL::type(DealerImageItemType::class))),

    ];
  }
}
