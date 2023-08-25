<?php

declare(strict_types=1);

namespace App\GraphQL\Types\Digest;

use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

final class RegionType extends GraphQLType
{
  protected string $name = 'Region';

  public function fields(): array
  {
    return [
      Field::make('id')
        ->type(Type::int()),

      Field::make('title')
        ->type(Type::string())
    ];
  }
}
