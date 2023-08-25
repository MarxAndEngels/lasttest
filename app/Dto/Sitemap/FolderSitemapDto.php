<?php

declare(strict_types=1);

namespace App\Dto\Sitemap;

use App\Complex\Dto\Dto;
use Carbon\Carbon;

class FolderSitemapDto extends Dto
{
  public string $title;
  public string $slug;
  public int $id;
  public ?MarkSitemapDto $mark;

  public function createSitemapUrl(string $siteUrl, string $categoryUrl): array
  {
    return [
      'loc' => "{$siteUrl}/{$categoryUrl}/{$this->mark->slug}/{$this->slug}",
      'lastmod' => Carbon::now()->startOfDay()->subDays(2)->toRfc3339String(),
      'changefreq' => 'daily',
      'priority' => '0.9'
    ];
  }
}
