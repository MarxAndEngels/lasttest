<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Bank;
use Illuminate\Support\Facades\Cache;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;

class BankObserver
{
  public function updated(Bank $bank): void
  {
    Nova::whenServing(function (NovaRequest $request) use ($bank) {
      $this->clearCacheBank($bank->slug);
    });
  }

  public function deleted(Bank $bank): void
  {
    Nova::whenServing(function (NovaRequest $request) use ($bank) {
      $this->clearCacheBank($bank->slug);
    });
  }

  protected function clearCacheBank(string $slug):void
  {
    Cache::tags("bank.{$slug}")->flush();
    Cache::tags("banks")->flush();
//    CacheTags::flushTags("bank.{$slug}");
//    CacheTags::flushTags('banks');
  }
}
