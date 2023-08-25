<?php

namespace App\Models;

use App\Constants\Attributes\AttributeName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedbackMegaCrm extends Model
{
  protected $casts = [
    AttributeName::LAST_REQUEST_AT => 'datetime',
    AttributeName::DOWNLOAD_AT => 'datetime'
  ];
  public function site(): BelongsTo
  {
    return $this->belongsTo(Site::class);
  }
}
