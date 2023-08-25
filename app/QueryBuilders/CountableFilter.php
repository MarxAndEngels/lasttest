<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use Illuminate\Support\Arr;
use Illuminate\Contracts\Support\Arrayable;

abstract class CountableFilter
{
  /**
   * A => B, (для поиска А необходимо не пустое значение B)
   */
  protected array $dependencies = [];
  protected array $excepts = [];

  public function build(array $countable): array
  {
    return collect($countable)
      ->filter(fn(string $name) => $this->isResolvable($name))
      ->mapWithKeys(fn(string $name) => [$name => call_user_func([$this, $name])])
      ->all();
  }

  abstract protected function hasInputValue(array $dependencies): bool;

  protected function getExceptsDescendants(string $name): array
  {
    return $this->excepts[$name] ?? [];
  }
  public function isResolvable(string $name): bool
  {
    if (
      !empty($this->dependencies)
      && Arr::exists($this->dependencies, $name)
    ) {
      return $this->hasInputValue($this->dependencies[$name]);
    }

    return true;
  }
}
