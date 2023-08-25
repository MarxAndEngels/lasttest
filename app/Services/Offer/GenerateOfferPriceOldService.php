<?php
declare(strict_types=1);

namespace App\Services\Offer;

use App\Helpers\CacheTags;
use App\Models\Offer;
use Illuminate\Support\Facades\Cache;
use function collect;

class GenerateOfferPriceOldService
{
  protected array $offer;
  protected ?float $generatedPriceOld;

  public function __construct(Offer $offer)
  {
    $this->offer = $offer->toArray();
    $cacheKeyPriceOld = CacheTags::getCacheKey("{$this->offer['external_id']}", null, 'priceOld');
    $cacheTags = ["offer.{$this->offer['external_id']}", "priceOld"];
    $this->generatedPriceOld = $this->offer['price_old'] ?? $this->offer['price'] + 200000;

    if(isset($this->offer['logic']) && $this->offer['logic']){
      if(!Cache::tags($cacheTags)->has($cacheKeyPriceOld)){
        $this->handle();
        Cache::tags($cacheTags)->forever($cacheKeyPriceOld, $this->generatedPriceOld);
//        CacheTags::forever(["offer.{$this->offer['external_id']}", "priceOld"], $cacheKeyPriceOld, $this->generatedPriceOld);
      }else{
        $this->generatedPriceOld = (float)Cache::tags($cacheTags)->get($cacheKeyPriceOld);
      }
    }
  }

  public function getGeneratedPriceOld(): float
  {
    return $this->generatedPriceOld;
  }

  protected function handle()
  {
    $logic = collect($this->offer['logic'])->first(function ($item) {
      $condition = $item['condition'];
      $bool = false;
      foreach ($condition as $key => $entry) {
        ['value' => $value, 'operator' => $operator, 'field' => $field] = $entry;
        if (!isset($this->offer[$field])) {
          return false;
        }
        $offerValue = $this->offer[$field];
        switch ($operator) {
          case '>':
            if ($offerValue > $value && ($key == 0 || $bool)) {
              $bool = true;
            }else{
              $bool = false;
            }
            break;
          case '>=':
            if ($offerValue >= $value && ($key == 0 || $bool)) {
              $bool = true;
            }else{
              $bool = false;
            }
            break;
          case '<':
            if ($offerValue < $value && ($key == 0 || $bool)) {
              $bool = true;
            }else{
              $bool = false;
            }
            break;
          case '<=':
            if ($offerValue <= $value && ($key == 0 || $bool)) {
              $bool = true;
            }else{
              $bool = false;
            }
            break;
          case '=':
            if ($offerValue == $value && ($key == 0 || $bool)) {
              $bool = true;
            }else{
              $bool = false;
            }
            break;
          case '==':
            if ((isset($this->offer[$value]) && $offerValue == $this->offer[$value]) && ($key == 0 || $bool)) {
              $bool = true;
            }else{
              $bool = false;
            }
            break;
          default:
            $bool = false;
        }
      }
      return $bool;
    });
    if(!$logic){
      return;
    }
    ['type' => $type, 'value' => $value, 'rounding' => $rounding] = $logic;
    switch ($type){
      case "sum":
        $this->generatedPriceOld = $this->offer['price'] + $value;
      break;
      case "percent":
        $this->generatedPriceOld = $this->offer['price'] + ($this->offer['price'] / 100 * $value);
      break;
    }
    if($this->generatedPriceOld){
      $this->generatedPriceOld = round($this->generatedPriceOld, $rounding);
    }
  }
}
