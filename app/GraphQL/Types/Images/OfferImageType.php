<?php

namespace App\GraphQL\Types\Images;


use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

final class OfferImageType extends GraphQLType
{
  protected string $name = 'OfferImage';

  public function fields(): array
  {
    return [
      Field::make('tiny')->type(Type::string()),

      Field::make('tiny_webp')
        ->type(Type::string()),

      Field::make('thumb')->type(Type::string())
        ->deprecationReason('Используйте small'),
      Field::make('small')->type(Type::string()),
      Field::make('small_webp')->type(Type::string()),

      Field::make('src')->type(Type::string())
        ->deprecationReason('Используйте medium'),
      Field::make('medium')->type(Type::string()),
      Field::make('medium_webp')->type(Type::string()),

      Field::make('original')->type(Type::string()),
    ];
  }
}
