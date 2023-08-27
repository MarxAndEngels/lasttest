<?php

namespace App\GraphQL\Types\Content;


use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use App\Constants\Attributes\AttributeName;
use App\GraphQL\Types\Images\BankImageCarItemType;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

final class BankType extends GraphQLType
{
  protected string $name = 'Bank';

  public function fields(): array
  {
    return [
      Field::make(AttributeName::ID)
        ->type(Type::nonNull(Type::int())),

      Field::make(AttributeName::NAME)
        ->type(Type::nonNull(Type::string())),

      Field::make(AttributeName::TITLE)
        ->type(Type::string()),

      Field::make(AttributeName::SLUG)
        ->type(Type::nonNull(Type::string())),

      Field::make(AttributeName::LICENSE_TITLE)
        ->type(Type::string()),
    ];
  }
}
