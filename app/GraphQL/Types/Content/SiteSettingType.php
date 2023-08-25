<?php

namespace App\GraphQL\Types\Content;


use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use App\Constants\Attributes\AttributeName;
use App\GraphQL\Types\KeyValueType;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

final class SiteSettingType extends GraphQLType
{
  protected string $name = 'SiteSetting';

  public function fields(): array
  {
    return [
      Field::make(AttributeName::ID)
        ->type(Type::nonNull(Type::int())),


      Field::make(AttributeName::SETTINGS)
        ->resolve(fn(array $siteSetting) => collect($siteSetting[AttributeName::SETTINGS])->map(fn($value, $key) => ['key' => $key, 'value' => $value])->all())
        ->type(Type::listOf(GraphQL::type(KeyValueType::class))),

    ];
  }
}
