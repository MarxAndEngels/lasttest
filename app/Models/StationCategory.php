<?php

namespace App\Models;

use App\Constants\Attributes\AttributeName;
use App\QueryBuilders\StationCategoryQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class StationCategory extends Model implements Sortable
{
  use SortableTrait;
  public $timestamps = true;

  public array $sortable = [
    'order_column_name' => AttributeName::ORDER_COLUMN,
    'sort_when_creating' => true,
  ];
  protected $guarded = [];
  protected string $builder = StationCategoryQueryBuilder::class;

  public function newEloquentBuilder($query): StationCategoryQueryBuilder
  {
    return new StationCategoryQueryBuilder($query);
  }

  public function site(): BelongsTo
  {
    return $this->belongsTo(Site::class);
  }

  public function stations(): HasMany
  {
    return $this->hasMany(Station::class);
  }
}
