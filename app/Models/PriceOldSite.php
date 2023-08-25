<?php

namespace App\Models;

use App\Complex\Eloquent\ReferenceModel;
use App\Constants\Attributes\AttributeName;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceOldSite extends ReferenceModel
{
  public $timestamps = true;
  protected $casts = [
    AttributeName::LOGIC => 'array',
    AttributeName::CREATED_AT => 'datetime',
    AttributeName::UPDATED_AT => 'datetime'
  ];

  public function site(): BelongsTo
  {
    return $this->belongsTo(Site::class);
  }
}
