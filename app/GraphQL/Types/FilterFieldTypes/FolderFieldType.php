<?php

declare(strict_types=1);

namespace App\GraphQL\Types\FilterFieldTypes;

use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

class FolderFieldType extends GraphQLType
{
  protected string $name = 'FolderField';

  public function fields(): array
  {
    return [
      Field::make('title')
        ->type(Type::string()),

      Field::make('id')
        ->type(Type::int()),

      Field::make('slug')
        ->type(Type::string()),

      Field::make('mark_id')
        ->type(Type::int()),
    ];
  }
}
