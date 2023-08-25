<?php

declare(strict_types=1);

namespace App\Dto\Sitemap;

use App\Complex\Dto\Dto;
use Carbon\Carbon;

class MarkSitemapDto extends Dto
{
  public string $title;
  public string $slug;
  public int $id;

  public function createSitemapUrl(string $siteUrl, string $categoryUrl): array
  {
    return [
      'loc' => "{$siteUrl}/{$categoryUrl}/{$this->slug}",
      'lastmod' => Carbon::now()->startOfDay()->subDays(2)->toRfc3339String(),
      'changefreq' => 'daily',
      'priority' => '0.9'
    ];
  }
}
