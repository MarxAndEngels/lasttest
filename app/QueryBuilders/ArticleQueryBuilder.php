<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Constants\Attributes\AttributeName;
use App\Constants\TableConstants;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

final class ArticleQueryBuilder extends Builder
{

  public function whereUrl(string $url): self
  {
    return $this->where($this->qualifyColumn(AttributeName::URL), '=', $url);
  }
  public function whereActive(): self
  {
    return
      $this->where($this->qualifyColumn(AttributeName::IS_ACTIVE), '=', true)
           ->where($this->qualifyColumn(AttributeName::PUBLISHED_AT), '<=', Carbon::now());
  }
  public function whereCategoryUrl(string $categoryUrl): self
  {
    return $this->whereHas('category', fn($builder) => $builder->whereUrl($categoryUrl));
  }
}
