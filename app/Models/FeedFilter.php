<?php

namespace App\Models;

use App\Complex\Eloquent\ReferenceModel;
use App\Constants\Attributes\AttributeName;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedFilter extends ReferenceModel
{
  public $timestamps = true;
  protected $casts = [
    AttributeName::FILTER => 'array',
    AttributeName::GENERATE_FILE => 'boolean',
    AttributeName::FEED_YANDEX_XML => 'boolean',
    AttributeName::FEED_YANDEX_YML => 'boolean',
    AttributeName::FEED_VK_XML => 'boolean',
    AttributeName::GENERATE_FILE_AT => 'datetime',
    AttributeName::DOWNLOAD_AT => 'datetime'
  ];
  public function site(): BelongsTo
  {
    return $this->belongsTo(Site::class);
  }
}
