<?php

namespace App\GraphQL\Types\TechnicalTypes;


use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use App\Constants\Attributes\AttributeName;
use GraphQL\Type\Definition\Type;

class TitleNameType extends GraphQLType
{
  protected string $name = 'TitleName';

  public function fields(): array
  {
    return [
      Field::make(AttributeName::ID)
        ->type(Type::nonNull(Type::int())),

      Field::make('title')
        ->type(Type::string()),

      Field::make('name')
        ->type(Type::string())
    ];
  }
}
