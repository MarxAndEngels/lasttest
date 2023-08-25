<?php
declare(strict_types=1);

namespace App\GraphQL\Types\ParseUrlTypes;

use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

final class OfferUrlFilterPaginationType extends GraphQLType
{
  protected string $name = 'OfferUrlFilterPaginationType';

  public function fields(): array
  {
    return [
      Field::make('mark_slug_array')
        ->type(Type::listOf(Type::string())),
      Field::make('folder_slug_array')
        ->type(Type::listOf(Type::string())),
      Field::make('generation_slug_array')
        ->type(Type::listOf(Type::string())),
      Field::make('year_from')
        ->type(Type::int()),
      Field::make('engine_type_id_array')
        ->type(Type::listOf(Type::int())),
      Field::make('body_type_id_array')
        ->type(Type::listOf(Type::string())),
      Field::make('category')
        ->type(Type::string())
    ];
  }
}
