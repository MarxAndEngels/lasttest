<?php

namespace App\Models;

use App\Complex\Eloquent\ReferenceModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EngineType extends ReferenceModel
{
  public function offers(): HasMany
  {
    return $this->hasMany(Offer::class);
  }
}