<?php
namespace App\Services\Site;


use App\Constants\Attributes\AttributeName;
use App\Models\Site;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class getSiteService
{
  public array $siteArray;
  public function __construct(int $siteId)
  {
    $siteQuery = Site::query();
    $this->siteArray =  Cache::rememberForever("siteArray.{$siteId}", fn() => $siteQuery->selectForFields()->whereId($siteId)->first()->toArray());
  }

  public function getParentSiteId() : int
  {
    if ($this->siteArray[AttributeName::PARENT_SITE_ID]){
      return $this->siteArray[AttributeName::PARENT_SITE_ID];
    }else{
      return $this->siteArray[AttributeName::ID];
    }
  }

}
