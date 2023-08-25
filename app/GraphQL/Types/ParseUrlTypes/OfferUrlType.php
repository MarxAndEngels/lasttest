<?php
declare(strict_types=1);

namespace App\GraphQL\Types\ParseUrlTypes;

use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

final class OfferUrlType extends GraphQLType
{
  protected string $name = 'OfferUrlType';

  public function fields(): array
  {
    return [
      Field::make('mark_slug')
        ->type(Type::string()),
      Field::make('folder_slug')
        ->type(Type::string()),
      Field::make('external_id')
        ->type(Type::int()),
    ];
  }
}
