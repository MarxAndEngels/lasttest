<?php

namespace App\Models;

use App\Constants\Attributes\AttributeName;
use App\Constants\TableConstants;
use App\QueryBuilders\FeedbackQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;


/** @method static FeedbackQueryBuilder query() */
class Feedback extends Model
{
  protected $guarded = [];
  protected $casts = [
    AttributeName::CREATED_AT => 'datetime'
  ];
  protected $table = TableConstants::FEEDBACKS;
  protected string $builder = FeedbackQueryBuilder::class;

  public function newEloquentBuilder($query): FeedbackQueryBuilder
  {
    return new FeedbackQueryBuilder($query);
  }
  public function site() : BelongsTo
  {
    return $this->belongsTo(Site::class);
  }
  public function feedbackOffer(): HasOne
  {
    return $this->hasOne(FeedbackOffer::class);
  }

}
