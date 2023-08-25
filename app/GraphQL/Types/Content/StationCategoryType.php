<?php

namespace App\GraphQL\Types\Content;


use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use App\Constants\Attributes\AttributeName;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

final class StationCategoryType extends GraphQLType
{
  protected string $name = 'StationCategory';

  public function fields(): array
  {
    return [
      Field::make(AttributeName::ID)
        ->type(Type::nonNull(Type::int())),

      Field::make(AttributeName::TITLE)
        ->type(Type::string()),

      Field::make('stations')
        ->type(Type::listOf(GraphQL::type(StationType::class))),

    ];
  }
}
