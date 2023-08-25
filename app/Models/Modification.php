<?php

namespace App\Models;

use App\Complex\Eloquent\ReferenceModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Modification extends ReferenceModel
{
  public function generation() : BelongsTo
  {
    return $this->belongsTo(Generation::class);
  }
  public function bodyType(): BelongsTo
  {
    return $this->belongsTo(BodyType::class);
  }
}
