<?php

declare(strict_types=1);

namespace App\Observers;

use App\Helpers\CacheTags;
use App\Models\Site;
use App\Models\SiteSetting;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;

class SiteObserver
{
  public function updated(Site $site)
  {
    Nova::whenServing(function (NovaRequest $request) use ($site) {
      $this->clearCacheSite($site->id);
    });
  }

  public function deleted(Site $site)
  {
    Nova::whenServing(function (NovaRequest $request) use ($site) {
      $this->clearCacheSite($site->id);
    });
  }

  protected function clearCacheSite(int $siteId): bool
  {
    return \Cache::forget("site.{$siteId}");
  }
}
