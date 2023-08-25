<?php

namespace App\GraphQL\Types\TechnicalTypes;


use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use App\Constants\Attributes\AttributeName;
use GraphQL\Type\Definition\Type;

class RatingType extends GraphQLType
{
  protected string $name = 'Rating';

  public function fields(): array
  {
    return [
      Field::make(AttributeName::RATING_TOTAL)
        ->type(Type::float()),

      Field::make(AttributeName::RATING_INTERIOR)
        ->type(Type::float()),

      Field::make(AttributeName::RATING_BODY)
        ->type(Type::float()),

      Field::make(AttributeName::RATING_TECHNICAL)
        ->type(Type::float()),
    ];
  }
}
