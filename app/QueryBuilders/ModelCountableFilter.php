<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Complex\Support\Arr;
use Closure;

abstract class ModelCountableFilter extends CountableFilter
{
  protected ?array $input;
  protected Closure $queryBuilder;

  public function __construct(Closure $queryBuilder, array $input = null)
  {
    $this->input = $input;
    $this->queryBuilder = $queryBuilder;
  }

  protected function hasInputValue(array $dependencies): bool
  {
    return Arr::getAny($this->input, $dependencies);
  }
}
