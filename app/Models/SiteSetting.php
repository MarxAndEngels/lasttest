<?php

namespace App\Models;

use App\Complex\Eloquent\ReferenceModel;
use App\Constants\Attributes\AttributeName;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteSetting extends ReferenceModel
{
  public $timestamps = true;
  protected $casts = [
    AttributeName::SETTINGS => 'array',
  ];
  public function site(): BelongsTo
  {
    return $this->belongsTo(Site::class);
  }
}
