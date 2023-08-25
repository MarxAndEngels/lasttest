<?php

namespace App\Models;

use App\Constants\Attributes\AttributeName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelegramChannelSite extends Model
{
  protected $guarded = [];
  protected $casts = [
    AttributeName::FILTER => 'array',
    AttributeName::SEND_AT => 'datetime'
  ];
  public function site(): BelongsTo
  {
    return $this->belongsTo(Site::class);
  }
}
