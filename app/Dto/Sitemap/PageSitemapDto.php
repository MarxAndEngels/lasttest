<?php

declare(strict_types=1);

namespace App\Dto\Sitemap;

use App\Complex\Dto\Dto;
use Carbon\Carbon;

class PageSitemapDto extends Dto
{
  public string $slug;

  public function createSitemapUrl(string $siteUrl): array
  {
    return [
      'loc' => "{$siteUrl}/{$this->slug}",
      'lastmod' => Carbon::now()->startOfDay()->subDays(2)->toRfc3339String(),
      'changefreq' => 'daily',
      'priority' => '1.0'
    ];
  }
}
