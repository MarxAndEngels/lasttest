<?php

declare(strict_types=1);

namespace App\GraphQL\Types\FilterFieldTypes;

use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ChosenFieldType extends GraphQLType
{
  protected string $name = 'ChosenFieldType';

  public function fields(): array
  {
    return [

      Field::make('mark')
        ->type(Type::listOf(GraphQL::type(MarkFieldType::class))),

      Field::make('folder')
        ->type(Type::listOf(GraphQL::type(FolderFieldType::class))),

      Field::make('generation')
        ->type(Type::listOf(GraphQL::type(GenerationFieldType::class))),

      Field::make('gearbox')
        ->type(Type::listOf(GraphQL::type(GearboxFieldType::class))),

      Field::make('engineType')
        ->type(Type::listOf(GraphQL::type(EngineTypeFieldType::class))),

      Field::make('driveType')
        ->type(Type::listOf(GraphQL::type(DriveTypeFieldType::class))),

      Field::make('bodyType')
        ->type(Type::listOf(GraphQL::type(BodyTypeFieldType::class))),

      Field::make('owner')
        ->type(Type::listOf(GraphQL::type(OwnerFieldType::class))),

      Field::make('yearFrom')
        ->type(Type::int()),
      Field::make('yearTo')
        ->type(Type::int()),
//
//      Field::make('run')
//        ->type(Type::listOf(Type::int())),
//
//      Field::make('enginePower')
//        ->type(Type::listOf(Type::int())),
//
      Field::make('priceFrom')
        ->type(Type::int()),
      Field::make('priceTo')
        ->type(Type::int()),
      Field::make('runFrom')
        ->type(Type::int()),
      Field::make('runTo')
        ->type(Type::int())
    ];
  }
}
