<?php

namespace App\Models;

use App\Complex\Eloquent\ReferenceModel;
use App\Constants\Attributes\AttributeName;
use App\QueryBuilders\ArticleCategoryQueryBuilder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;
class ArticleCategory extends ReferenceModel
{
  use HasEagerLimit;
  public $timestamps = true;

  public $casts = [
    AttributeName::CREATED_AT => 'datetime',
    AttributeName::UPDATED_AT => 'datetime'
  ];

  protected string $builder = ArticleCategoryQueryBuilder::class;

  public function newEloquentBuilder($query): ArticleCategoryQueryBuilder
  {
    return new ArticleCategoryQueryBuilder($query);
  }

  public function articles(): HasMany
  {
    return $this->hasMany(Article::class);
  }
}
