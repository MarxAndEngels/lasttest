<?php

namespace App\GraphQL\Types\Content;


use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use App\Constants\Attributes\AttributeName;
use GraphQL\Type\Definition\Type;

final class SiteTextType extends GraphQLType
{
  protected string $name = 'SiteText';

  public function fields(): array
  {
    return [
      Field::make(AttributeName::BODY)
        ->type(Type::string()),
    ];
  }
}
