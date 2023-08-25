<?php

namespace App\GraphQL\Types\MarkFolderGeneration;


use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use App\Constants\Attributes\AttributeName;
use App\GraphQL\Types\TechnicalTypes\GenerationType;
use App\Models\Folder;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

final class FolderGenerationType extends GraphQLType
{
  protected string $name = 'FolderGeneration';

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

      Field::make('generations')
        ->type(Type::listOf(GraphQL::type(GenerationType::class)))
    ];
  }
}