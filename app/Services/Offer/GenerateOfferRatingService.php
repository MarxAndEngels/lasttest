<?php
declare(strict_types=1);

namespace App\Services\Offer;

use App\Helpers\CacheTags;
use Illuminate\Support\Facades\Cache;

class GenerateOfferRatingService
{

  protected int $year;
  protected int $run;
  protected int $external_id;
  protected float $rating = 4.0;
  protected float $rating_technical = 4.0;
  protected float $rating_body = 4.0;
  protected float $rating_interior = 4.0;

  public function __construct(int $external_id, int $year, int $run)
  {
    $this->external_id = $external_id;
    $this->year = $year;
    $this->run = $run;
    $cacheKeyRating = CacheTags::getCacheKey("{$external_id}", null, 'rating');
    $cacheKeyTechnicalRating = CacheTags::getCacheKey("{$external_id}", null, 'rating.technical');
    $cacheKeyBodyRating = CacheTags::getCacheKey("{$external_id}", null, 'rating.body');
    $cacheKeyInteriorRating = CacheTags::getCacheKey("{$external_id}", null, 'rating.interior');
    $cacheTags = ["offer.{$external_id}", "rating"];

    if(!Cache::tags($cacheTags)->has($cacheKeyRating) && !Cache::tags($cacheTags)->has($cacheKeyTechnicalRating) && !Cache::tags($cacheTags)->has($cacheKeyBodyRating) && !Cache::tags($cacheTags)->has($cacheKeyInteriorRating)){
      $this->handle();
      Cache::tags($cacheTags)->forever($cacheKeyRating, $this->rating);
      Cache::tags($cacheTags)->forever($cacheKeyTechnicalRating, $this->rating_technical);
      Cache::tags($cacheTags)->forever($cacheKeyBodyRating, $this->rating_body);
      Cache::tags($cacheTags)->forever( $cacheKeyInteriorRating, $this->rating_interior);
//      CacheTags::forever(["offer.{$external_id}", "rating"], $cacheKeyRating, $this->rating);
//      CacheTags::forever(["offer.{$external_id}", "rating"], $cacheKeyTechnicalRating, $this->rating_technical);
//      CacheTags::forever(["offer.{$external_id}", "rating"], $cacheKeyBodyRating, $this->rating_body);
//      CacheTags::forever(["offer.{$external_id}", "rating"], $cacheKeyInteriorRating, $this->rating_interior);
    }else{
      $this->rating = (float)Cache::tags($cacheTags)->get($cacheKeyRating);
      $this->rating_technical = (float)Cache::tags($cacheTags)->get($cacheKeyTechnicalRating);
      $this->rating_body = (float)Cache::tags($cacheTags)->get($cacheKeyBodyRating);
      $this->rating_interior = (float)Cache::tags($cacheTags)->get( $cacheKeyInteriorRating);

//      $this->rating = CacheTags::get($cacheKeyRating);
//      $this->rating_technical = CacheTags::get($cacheKeyTechnicalRating);
//      $this->rating_body = CacheTags::get($cacheKeyBodyRating);
//      $this->rating_interior = CacheTags::get($cacheKeyInteriorRating);


    }
  }


  public function getRating(): float
  {
    return $this->rating;
  }

  public function getRatingTechnical(): float
  {
    return $this->rating_technical;
  }

  public function getRatingBody(): float
  {
    return $this->rating_body;
  }

  public function getRatingInterior(): float
  {
    return $this->rating_interior;
  }

  protected function mtRand(float $min, float $max)
  {
    $a = $min * 2;
    $b = $max * 2;
    return mt_rand((int)$a, (int)$b) / 2;
  }
  protected function handle()
  {
    if ($this->run <= 80000) {
      $this->rating_technical = 5;
      $this->rating_body = $this->mtRand(4.5, 5);
      $this->rating_interior = 5;
    }
    if ($this->run > 80000 && $this->run <= 120000) {
      $this->rating_technical = 5;
      $this->rating_body = $this->mtRand(4, 4.5);
      $this->rating_interior = 4;
    }
    if ($this->run > 120000) {
      if ($this->year < 2003) {
        $this->rating_technical = 3;
        $this->rating_body = $this->mtRand(3.5, 4);
        $this->rating_interior  = $this->mtRand(3.5, 4);
      }
      if ($this->year >= 2003 && $this->year <= 2008) {
        $this->rating_technical = 4;
        $this->rating_body = $this->mtRand(3.5, 4);
        $this->rating_interior  = $this->mtRand(3.5, 4);
      }
      if ($this->year >= 2009 && $this->year <= 2014) {
        $this->rating_technical = $this->mtRand(4, 4.5);
        $this->rating_body = $this->mtRand(3.5, 4);
        $this->rating_interior  = $this->mtRand(3.5, 4);
      }
      if ($this->year >= 2015) {
        $this->rating_technical = $this->mtRand(4.5, 5);
        $this->rating_body = $this->mtRand(4.5, 5);
        $this->rating_interior  = $this->mtRand(4.5, 5);
      }
    }
    $rating_val_total = ($this->rating_technical + $this->rating_body + $this->rating_interior ) / 3;
    $this->rating = ceil($rating_val_total/0.5)*0.5;
  }
}
