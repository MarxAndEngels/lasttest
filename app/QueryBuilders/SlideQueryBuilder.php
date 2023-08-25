<?php

namespace App\QueryBuilders;

use App\Constants\Attributes\AttributeName;
use Illuminate\Database\Eloquent\Builder;

final class SlideQueryBuilder extends Builder
{
  public function whereSiteId(int $siteId): self
  {
    return $this->whereHas('sites', fn(SiteQueryBuilder $site) => $site->whereId($siteId));
  }

  public function whereActive(): self
  {
    return $this->where($this->qualifyColumn(AttributeName::IS_ACTIVE), '=', true);
  }

  public function orderByColumn(): self
  {
    return $this->orderBy($this->qualifyColumn(AttributeName::ORDER_COLUMN), 'ASC');
  }
}
