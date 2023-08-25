<?php

declare(strict_types=1);

namespace App\GraphQL\Types\TechnicalTypes;


use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use App\Constants\Attributes\AttributeName;
use GraphQL\Type\Definition\Type;

class ComplectationType extends GraphQLType
{
  protected string $name = 'Complectation';
  public function fields(): array
  {
    return [
      Field::make(AttributeName::ID)
        ->type(Type::nonNull(Type::int())),

      Field::make('name')
        ->type(Type::string())
    ];
  }
}
