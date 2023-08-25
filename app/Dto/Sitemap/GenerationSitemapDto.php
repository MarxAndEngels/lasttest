<?php

declare(strict_types=1);

namespace App\Dto\Sitemap;

use App\Complex\Dto\Dto;
use Carbon\Carbon;

class GenerationSitemapDto extends Dto
{
  public string $name;
  public string $slug;
  public ?string $category_enum;
  public int $id;
  public ?MarkSitemapDto $mark;
  public ?FolderSitemapDto $folder;

  public function createSitemapUrl(string $siteUrl, array $categoryAssociation): array
  {
    return [
      'loc' => "{$siteUrl}/{$categoryAssociation[$this->category_enum]}/{$this->mark->slug}/{$this->folder->slug}/{$this->slug}",
      'lastmod' => Carbon::now()->startOfDay()->subDays(2)->toRfc3339String(),
      'changefreq' => 'daily',
      'priority' => '0.9'
    ];
  }
}
