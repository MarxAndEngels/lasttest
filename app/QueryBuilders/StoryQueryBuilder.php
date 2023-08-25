<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Constants\Attributes\AttributeName;
use App\Constants\TableConstants;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;

final class StoryQueryBuilder extends Builder
{
  public function whereSiteId(int $siteId): self
  {
    return $this->whereHas('sites', fn(SiteQueryBuilder $site) => $site->whereId($siteId));
  }

  public function whereNotTitle(string $title): self
  {
    return $this->where($this->qualifyColumn(AttributeName::TITLE), '!=', $title);
  }

  public function whereNotTitleArray(array $weekTitles): self
  {
    return $this->whereNotIn($this->qualifyColumn(AttributeName::TITLE), $weekTitles);
  }

  public function whereActive(): self
  {
    return $this->where($this->qualifyColumn(AttributeName::IS_ACTIVE), '=', true)
      ->whereHas('stories', fn(Builder $query)=> $query->where($query->qualifyColumn(AttributeName::IS_ACTIVE), '=', true));
  }

  public function orderByColumn(): self
  {
    return $this->orderBy($this->qualifyColumn(AttributeName::ORDER_COLUMN), 'ASC');
  }
}
