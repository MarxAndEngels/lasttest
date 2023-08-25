<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

final class TitleValueType extends GraphQLType
{
  protected string $name = 'TitleValue';

  public function fields(): array
  {
    return [
      Field::make('title')
        ->type(Type::string()),

      Field::make('value')
        ->type(Type::string())
    ];
  }
}
