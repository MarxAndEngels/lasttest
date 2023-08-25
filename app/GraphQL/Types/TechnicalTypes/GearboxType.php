<?php

namespace App\GraphQL\Types\TechnicalTypes;


use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use App\Constants\Attributes\AttributeName;
use GraphQL\Type\Definition\Type;

class GearboxType extends GraphQLType
{
  protected string $name = 'Gearbox';

  public function fields(): array
  {
    return [
      Field::make(AttributeName::ID)
        ->type(Type::nonNull(Type::int())),

      Field::make(AttributeName::TITLE)
        ->type(Type::string()),

      Field::make(AttributeName::NAME)
        ->type(Type::string()),

      Field::make(AttributeName::TITLE_SHORT)
        ->type(Type::string()),

      Field::make(AttributeName::TITLE_SHORT_RUS)
        ->type(Type::string())
    ];
  }
}
