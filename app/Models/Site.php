<?php

namespace App\Models;

use App\Complex\Eloquent\ReferenceModel;
use App\Constants\Attributes\AttributeName;
use App\Constants\TableConstants;
use App\QueryBuilders\SiteQueryBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Nova\Actions\Actionable;

/** @method static SiteQueryBuilder query() */
class Site extends ReferenceModel
{
  use Actionable;
  public $timestamps = true;
  protected $casts = [
    AttributeName::FILTER => 'array',
    AttributeName::CATEGORY_ASSOCIATION => 'array',
    AttributeName::ROUTE_PAGES => 'array',
    AttributeName::GENERATION_URL => 'bool',
    AttributeName::POST_FEEDBACK_EMAIL => 'bool',
    AttributeName::POST_FEEDBACK_PLEX_CRM => 'bool',
    AttributeName::GET_COMMUNICATIONS => 'bool',
    AttributeName::API_DATE_FROM => 'datetime',
    AttributeName::API_DATE_LAST => 'datetime'
  ];
  protected string $builder = SiteQueryBuilder::class;

  public function offers(): BelongsToMany
  {
    return $this->belongsToMany(Site::class);
  }
  public function settings(): HasMany
  {
    return $this->hasMany(SiteSetting::class);
  }
  public function offerSites(): BelongsToMany
  {
    return $this->belongsToMany(Offer::class, TableConstants::OFFER_SITE, AttributeName::SITE_ID, AttributeName::ID)
      ->withPivot([AttributeName::PRICE, AttributeName::PRICE_OLD, AttributeName::IS_ACTIVE]);
  }
  public function newEloquentBuilder($query): SiteQueryBuilder
  {
    return new SiteQueryBuilder($query);
  }
  public function regions(): BelongsToMany
  {
    return $this->belongsToMany(Region::class, TableConstants::REGION_SITE)->withPivot(AttributeName::ORDER_COLUMN);
  }
  public function dealer() : BelongsTo
  {
    return $this->belongsTo(Dealer::class);
  }
  public function dealers() : BelongsToMany
  {
    return $this->belongsToMany(Dealer::class, TableConstants::DEALER_SITE, AttributeName::SITE_ID, AttributeName::DEALER_ID);
  }
  public function parentSite() : BelongsTo
  {
    return $this->belongsTo(Site::class);
  }

  public function childrenSites() : HasMany
  {
    return $this->hasMany(Site::class, AttributeName::PARENT_SITE_ID, AttributeName::ID);
  }
  public function priceOldLogic(): BelongsTo
  {
    return $this->belongsTo(PriceOldSite::class);
  }
}
