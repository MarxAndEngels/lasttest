<?php

declare(strict_types=1);

namespace App\Complex\Eloquent;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder as BaseBuilder;

class Builder extends BaseBuilder
{

  public function whereField(string $column, $value) : self
  {
    if (is_array($value) || $value instanceof Arrayable) {
      return $this->whereIn($this->model->qualifyColumn($column), $value);
    }

    return $this->where($this->model->qualifyColumn($column), '=', $value);
  }
}
