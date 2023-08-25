<?php

namespace App\Observers;

use App\Models\SeoTag;
use Illuminate\Support\Facades\Cache;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;

class SeoTagObserver
{
  public function updated(SeoTag $seoTag): void
  {
    Nova::whenServing(function (NovaRequest $request) use ($seoTag) {
      $this->clearCacheSeoTag($seoTag->site_id);
    });
  }

  public function deleted(SeoTag $seoTag): void
  {
    Nova::whenServing(function (NovaRequest $request) use ($seoTag) {
      $this->clearCacheSeoTag($seoTag->site_id);
    });
  }

  protected function clearCacheSeoTag(int $siteId): void
  {
    Cache::tags("seoTag.{$siteId}")->flush();
//    CacheTags::flushTags("seoTag.{$siteId}");
  }
}
