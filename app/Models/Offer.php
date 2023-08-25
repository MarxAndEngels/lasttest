<?php

namespace App\Models;

use App\Constants\Attributes\AttributeName;
use App\Constants\TableConstants;
use App\ModelFilters\OfferFilter;
use App\QueryBuilders\OfferQueryBuilder;
use App\Services\Offer\GenerateOfferRatingService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** @method static OfferQueryBuilder query() */
class Offer extends Model
{

  protected string $builder = OfferQueryBuilder::class;
  protected $guarded = [];
  protected $appends = ['rating'];
  protected $casts = [
    AttributeName::IMAGES => 'array',
    AttributeName::EQUIPMENT => 'array',
    AttributeName::EQUIPMENT_GROUPS => 'array',
    AttributeName::SPECIFICATIONS => 'array',
    AttributeName::CATEGORY_ASSOCIATION => 'array',
    AttributeName::LOGIC => 'array',
    AttributeName::RATING => 'array',
    AttributeName::YEAR => 'int'
  ];


  public function newEloquentBuilder($query): OfferQueryBuilder
  {
    return new OfferQueryBuilder($query);
  }

  public function getRatingAttribute(): array
  {
    if (!$this->external_id || !$this->year || !$this->run) {
      return [
        AttributeName::RATING_TOTAL => 4.0,
        AttributeName::RATING_INTERIOR => 4.0,
        AttributeName::RATING_BODY => 4.0,
        AttributeName::RATING_TECHNICAL => 4.0,
      ];
    }
    $generateRating = new GenerateOfferRatingService($this->external_id, $this->year, $this->run);
    return [
      AttributeName::RATING_TOTAL => $generateRating->getRating(),
      AttributeName::RATING_INTERIOR => $generateRating->getRatingInterior(),
      AttributeName::RATING_BODY => $generateRating->getRatingBody(),
      AttributeName::RATING_TECHNICAL => $generateRating->getRatingTechnical(),
    ];
  }

  public function modelFilter()
  {
    return $this->provideFilter(OfferFilter::class);
  }

  public function offerSites(): BelongsToMany
  {
    return $this->belongsToMany(Site::class, TableConstants::OFFER_SITE)->withPivot([AttributeName::PRICE, AttributeName::PRICE_OLD, AttributeName::IS_ACTIVE]);
  }

  public function sites(): BelongsToMany
  {
    return $this->belongsToMany(Site::class);
  }

  public function mark(): BelongsTo
  {
    return $this->belongsTo(Mark::class);
  }

  public function folder(): BelongsTo
  {
    return $this->belongsTo(Folder::class);
  }

  public function generation(): BelongsTo
  {
    return $this->belongsTo(Generation::class);
  }

  public function modification(): BelongsTo
  {
    return $this->belongsTo(Modification::class);
  }

  public function complectation(): BelongsTo
  {
    return $this->belongsTo(Complectation::class);
  }

  public function gearbox(): BelongsTo
  {
    return $this->belongsTo(Gearbox::class);
  }

  public function driveType(): BelongsTo
  {
    return $this->belongsTo(DriveType::class);
  }

  public function engineType(): BelongsTo
  {
    return $this->belongsTo(EngineType::class);
  }

  public function bodyType(): BelongsTo
  {
    return $this->belongsTo(BodyType::class);
  }

  public function color(): BelongsTo
  {
    return $this->belongsTo(Color::class);
  }

  public function wheel(): BelongsTo
  {
    return $this->belongsTo(Wheel::class);
  }

  public function owner(): BelongsTo
  {
    return $this->belongsTo(Owner::class);
  }

  public function dealer(): BelongsTo
  {
    return $this->belongsTo(Dealer::class);
  }

  public function feedbackOffers(): HasMany
  {
    return $this->hasMany(FeedbackOffer::class, AttributeName::EXTERNAL_ID, AttributeName::EXTERNAL_ID);
  }

}
