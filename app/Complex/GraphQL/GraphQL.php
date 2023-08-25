<?php

declare(strict_types=1);

namespace App\Complex\GraphQL;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\GraphQL as VendorGraphQL;
use Rebing\GraphQL\Support\Contracts\TypeConvertible;

final class GraphQL extends VendorGraphQL
{
  public function type(string $name, bool $fresh = false): Type
  {
    return parent::type($this->getCustomTypeName($name), $fresh);
  }

  private function getCustomTypeName(string $name): string
  {
    if (is_subclass_of($name, TypeConvertible::class, true)) {
      return $this->app->make($name)->name;
    }
    return $name;
  }
}
