<?php

namespace App\GraphQL\Types\TechnicalTypes;


use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use App\Constants\Attributes\AttributeName;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

final class ModificationType extends GraphQLType
{
  protected string $name = 'Modification';

  public function fields(): array
  {
    return [
      Field::make(AttributeName::ID)
        ->type(Type::nonNull(Type::int())),

      Field::make('name')->type(Type::string()),

      Field::make('generation')->type(Type::listOf(GraphQL::type(GenerationType::class))),
      Field::make('bodyType')->type(Type::listOf(GraphQL::type(TitleNameType::class))),

    ];
  }
}
