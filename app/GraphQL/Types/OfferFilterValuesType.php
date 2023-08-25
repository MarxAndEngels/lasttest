<?php

namespace App\GraphQL\Types;


use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use App\GraphQL\Types\FilterFieldTypes\BodyTypeFieldType;
use App\GraphQL\Types\FilterFieldTypes\ChosenFieldType;
use App\GraphQL\Types\FilterFieldTypes\DriveTypeFieldType;
use App\GraphQL\Types\FilterFieldTypes\EngineTypeFieldType;
use App\GraphQL\Types\FilterFieldTypes\FolderFieldType;
use App\GraphQL\Types\FilterFieldTypes\GearboxFieldType;
use App\GraphQL\Types\FilterFieldTypes\GenerationFieldType;
use App\GraphQL\Types\FilterFieldTypes\MarkFieldType;
use App\GraphQL\Types\FilterFieldTypes\OwnerFieldType;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

final class OfferFilterValuesType extends GraphQLType
{
  protected string $name = 'OfferFilterValues';

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

      Field::make('year')
        ->type(Type::listOf(Type::int())),

      Field::make('run')
        ->type(Type::listOf(Type::int())),
//
//      Field::make('enginePower')
//        ->type(Type::listOf(Type::int())),

      Field::make('price')
        ->type(Type::listOf(Type::int())),

      Field::make('chosen')
        ->type(GraphQL::type(ChosenFieldType::class)),
    ];
  }
}
