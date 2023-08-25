<?php

declare(strict_types=1);

namespace App\GraphQL\Fields;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Field;

class CountOffersField extends Field
{

  public function type(): Type
  {
    return Type::int();
  }


}
