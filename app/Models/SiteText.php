<?php

namespace App\Models;

use App\Complex\Eloquent\ReferenceModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use \Illuminate\Database\Eloquent\Relations\MorphTo;

class SiteText extends ReferenceModel
{
  public $timestamps = true;

  public function model(): MorphTo
  {
    return $this->morphTo();
  }
  public function site(): BelongsTo
  {
    return $this->belongsTo(Site::class);
  }
}
