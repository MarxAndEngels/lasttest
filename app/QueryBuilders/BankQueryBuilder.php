<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Constants\Attributes\AttributeName;
use App\Constants\TableConstants;
use App\Models\SiteText;
use Illuminate\Database\Eloquent\Builder;

final class BankQueryBuilder extends Builder
{

  public function whereSlug(string $slug): self
  {
    return $this->where($this->qualifyColumn(AttributeName::SLUG), '=', $slug);
  }
  public function whereActive(): self
  {
    return $this->where($this->qualifyColumn(AttributeName::IS_ACTIVE), '=', true);
  }
  public function orderByRating(): self
  {
    return $this->orderBy($this->qualifyColumn(AttributeName::RATING), 'desc');
  }
  public function withText(int $siteId): self
  {
    return $this->with('siteText', fn($item) => $item->where(AttributeName::SITE_ID, '=', $siteId));
  }
}
