<?php

namespace App\Models;

use App\Complex\Eloquent\ReferenceModel;
use App\Constants\Attributes\AttributeName;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedbackOffer extends ReferenceModel
{
  protected $casts = [
    AttributeName::YEAR => 'int'
  ];

  public function mark(): BelongsTo
  {
    return $this->belongsTo(Mark::class);
  }
  public function folder(): BelongsTo
  {
    return $this->belongsTo(Folder::class);
  }
  public function gearbox(): BelongsTo
  {
    return $this->belongsTo(Gearbox::class);
  }
  public function generation(): BelongsTo
  {
    return $this->belongsTo(Generation::class);
  }
  public function modification(): BelongsTo
  {
    return $this->belongsTo(Modification::class);
  }
  public function feedback(): BelongsTo
  {
    return $this->belongsTo(Feedback::class);
  }
  public function dealer(): BelongsTo
  {
    return $this->belongsTo(Dealer::class);
  }
}
