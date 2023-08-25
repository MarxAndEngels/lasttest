<?php

namespace App\Models;

use App\Complex\Eloquent\ReferenceModel;
use App\Constants\Attributes\AttributeName;
use App\QueryBuilders\GenerationQueryBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Generation extends ReferenceModel
{
  protected string $builder = GenerationQueryBuilder::class;
  protected $casts = [
    AttributeName::YEAR_BEGIN => 'int',
    AttributeName::YEAR_END => 'int'
  ];

  public function folder() : BelongsTo
  {
    return $this->belongsTo(Folder::class);
  }
  public function offers(): HasMany
  {
    return $this->hasMany(Offer::class);
  }
  public function newEloquentBuilder($query): GenerationQueryBuilder
  {
    return new GenerationQueryBuilder($query);
  }

}
