<?php

namespace App\Models;

use App\Complex\Eloquent\ReferenceModel;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Mark extends ReferenceModel
{
  public function folders()
  {
    return $this->hasMany(Folder::class);
  }

  public function offers()
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
