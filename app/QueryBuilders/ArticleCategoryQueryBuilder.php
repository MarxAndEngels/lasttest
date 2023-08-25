<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Constants\Attributes\AttributeName;
use Illuminate\Database\Eloquent\Builder;

final class ArticleCategoryQueryBuilder extends Builder
{

  public function whereActive(): self
  {
    return $this->where($this->qualifyColumn(AttributeName::IS_ACTIVE), '=', true)
      ->whereHas('articles', fn(ArticleQueryBuilder $articleQuery) => $articleQuery->whereActive());
  }
  public function whereUrl(string $url): self
  {
    return $this->where($this->qualifyColumn(AttributeName::URL), '=', $url);
  }

  public function orderByCreatedAt(): self
  {
    return $this->orderBy($this->qualifyColumn(AttributeName::CREATED_AT), 'DESC');
  }
}
