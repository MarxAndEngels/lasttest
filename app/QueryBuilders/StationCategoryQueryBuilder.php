<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Constants\Attributes\AttributeName;
use App\Constants\TableConstants;
use Illuminate\Database\Eloquent\Builder;

final class StationCategoryQueryBuilder extends Builder
{
  public function whereSiteId(int $siteId): self
  {
    return $this->where($this->qualifyColumn(AttributeName::SITE_ID), '=', $siteId);
  }


  public function whereActive(): self
  {
    return $this->where($this->qualifyColumn(AttributeName::IS_ACTIVE), '=', true)
      ->whereHas('stations', fn(Builder $query)=> $query->where($query->qualifyColumn(AttributeName::IS_ACTIVE), '=', true));
  }

  public function orderByColumn(): self
  {
    return $this->orderBy($this->qualifyColumn(AttributeName::ORDER_COLUMN), 'ASC');
  }
}
