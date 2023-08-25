<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Dealer;
use Illuminate\Support\Facades\Cache;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;

class DealerObserver
{
  public function updated(Dealer $dealer): void
  {
    Nova::whenServing(function (NovaRequest $request) use ($dealer) {
      $this->clearCacheDealer($dealer->slug);
    });
  }

  public function deleted(Dealer $dealer): void
  {
    Nova::whenServing(function (NovaRequest $request) use ($dealer) {
      $this->clearCacheDealer($dealer->slug);
    });
  }

  protected function clearCacheDealer(string $slug): void
  {
    Cache::tags("dealer.{$slug}")->flush();
    Cache::tags("dealers")->flush();
//    CacheTags::flushTags("dealer.{$slug}");
//    CacheTags::flushTags('dealers');
  }
}
