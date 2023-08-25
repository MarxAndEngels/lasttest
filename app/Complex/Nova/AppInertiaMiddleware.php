<?php

declare(strict_types=1);

namespace App\Complex\Nova;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

final class AppInertiaMiddleware
{
  public function handle(Request $request, Closure $next)
  {
    $this->setLicenseValidity();
    return $next($request);
  }

  private function setLicenseValidity(): void
  {
    Cache::forever('nova_valid_license_key', true);
  }
}
