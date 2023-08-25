<?php

namespace App\Models;

use App\Complex\Eloquent\ReferenceModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Folder extends ReferenceModel
{

  public function mark(): BelongsTo
  {
    return $this->belongsTo(Mark::class);
  }
  public function generations(): HasMany
  {
    return $this->hasMany(Generation::class);
  }
  public function offers(): HasMany
  {
    return $this->hasMany(Offer::class);
  }
  public function siteTexts(): MorphMany
  {
    return $this->morphMany(SiteText::class, 'model');
  }
  public function siteText(): MorphOne
  {
    return $this->morphOne(SiteText::class, 'model');
  }
}
