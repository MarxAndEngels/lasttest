<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;

class SiteSettingObserver
{
  public function updated(SiteSetting $siteSetting): void
  {
    Nova::whenServing(function (NovaRequest $request) use ($siteSetting) {
      $this->clearCacheSiteSetting($siteSetting->site_id);
    });
  }

  public function deleted(SiteSetting $siteSetting): void
  {
    Nova::whenServing(function (NovaRequest $request) use ($siteSetting) {
      $this->clearCacheSiteSetting($siteSetting->site_id);
    });
  }

  protected function clearCacheSiteSetting(int $siteId): void
  {
    Cache::tags("siteSetting.{$siteId}")->flush();
//    CacheTags::flushTags("siteSetting.{$siteId}");
  }
}
