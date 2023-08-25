<?php

namespace App\Models;

use App\Complex\Eloquent\ReferenceModel;
use App\Constants\Attributes\AttributeName;
use App\Constants\TableConstants;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Region extends ReferenceModel
{
  public function sites(): BelongsToMany
  {
    return $this->belongsToMany(Site::class, TableConstants::REGION_SITE)->withPivot(AttributeName::ORDER_COLUMN);
  }
}
