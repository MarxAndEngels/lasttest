<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

final class KeyValueType extends GraphQLType
{
  protected string $name = 'KeyValue';

  public function fields(): array
  {
    return [
      Field::make('key')
        ->type(Type::string()),

      Field::make('value')
        ->type(Type::string())
    ];
  }
}
