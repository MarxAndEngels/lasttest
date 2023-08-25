<?php

declare(strict_types=1);

namespace App\QueryBuilders;

//use App\Exceptions\InfrastructureException;
use App\Complex\Eloquent\Builder;
use EloquentFilter\Filterable as BaseFilterable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use phpDocumentor\Reflection\Types\Static_;

/**
 * @see BaseFilterable
 */
trait Filterable
{
  use BaseFilterable;

  protected function modelCountableFilter(): string
  {
    return static::class;
  }

  public function filter(array $input = [], $filter = null): \Illuminate\Database\Eloquent\Builder
  {
    return $this->scopeFilter($this, $input, $filter);
  }

  public function paginateFilter($perPage = null, $columns = ['*'], $pageName = 'page', $page = null): LengthAwarePaginator
  {
    return $this->scopePaginateFilter($this, $perPage, $columns, $pageName, $page);
  }

  public function getCountableFilter(array $countable = [], array $input = [], $filter = null): array
  {
    $queryBuilder = fn($input) => (clone $this)->filter($input, $filter);
    $class = $this->modelCountableFilter();
    /** @var ModelCountableFilter $countableFilter */
    $countableFilter = new $class($queryBuilder, $input);

    return $countableFilter->build($countable);
  }
}
