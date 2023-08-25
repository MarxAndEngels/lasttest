<?php

declare(strict_types=1);

namespace App\GraphQL\Types\FilterFieldTypes;

use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use App\Constants\Attributes\AttributeName;
use GraphQL\Type\Definition\Type;

class GearboxFieldType extends GraphQLType
{
  protected string $name = 'GearboxField';

  public function fields(): array
  {
    return [
      Field::make(AttributeName::TITLE)
        ->type(Type::string()),

      Field::make(AttributeName::TITLE_SHORT)
        ->type(Type::string()),

      Field::make(AttributeName::TITLE_SHORT_RUS)
        ->type(Type::string()),

      Field::make(AttributeName::ID)
        ->type(Type::int()),

      Field::make(AttributeName::SLUG)
        ->type(Type::string())
    ];
  }
}
