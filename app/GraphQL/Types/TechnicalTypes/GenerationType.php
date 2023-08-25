<?php

namespace App\GraphQL\Types\TechnicalTypes;


use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use App\Constants\Attributes\AttributeName;
use GraphQL\Type\Definition\Type;

final class GenerationType extends GraphQLType
{
  protected string $name = 'Generation';

  public function fields(): array
  {
    return [
      Field::make(AttributeName::ID)
        ->type(Type::int()),

      Field::make('name')->type(Type::string()),
      Field::make('slug')->type(Type::nonNull(Type::string())),

      Field::make('year_begin')->type(Type::int()),
      Field::make('year_end')->type(Type::int()),
      Field::make('offers_count')
        ->type(Type::int())
        ->description('The count of generations')
        ->isNotSelectable(),
    ];
  }
}
