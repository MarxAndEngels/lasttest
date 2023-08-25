<?php

namespace App\GraphQL\Types\Digest;


use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use App\Constants\Attributes\AttributeName;
use GraphQL\Type\Definition\Type;

final class SeoTagCrumbsType extends GraphQLType
{
  protected string $name = 'SeoTagCrumbs';

  public function fields(): array
  {
    return [
      Field::make(AttributeName::TITLE)
        ->type(Type::string()),
      Field::make(AttributeName::LINK)
        ->type(Type::string()),
    ];
  }
}
