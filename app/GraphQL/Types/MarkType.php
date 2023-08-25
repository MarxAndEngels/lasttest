<?php

namespace App\GraphQL\Types;


use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use App\Constants\Attributes\AttributeName;
use App\Models\Mark;
use GraphQL\Type\Definition\Type;

final class MarkType extends GraphQLType
{
  protected string $name = 'Mark';

  public function fields(): array
  {
    return [
      Field::make(AttributeName::ID)
      ->type(Type::nonNull(Type::int()))
      ->description('Идентификатор'),

      Field::make('title')
        ->type(Type::nonNull(Type::string()))
        ->description('Наименование'),

      Field::make('title_rus')
        ->type(Type::string())
        ->description('Наименование на русском'),

      Field::make(AttributeName::SLUG)
        ->type(Type::nonNull(Type::string()))
        ->description('Алиас'),

      Field::make('offers_count')
        ->type(Type::int())
        ->description('Кол-во объявлений')
        ->isNotSelectable(),

      Field::make('order_column')
        ->type(Type::int())
        ->description('Сортировка марки'),

    ];
  }
}
