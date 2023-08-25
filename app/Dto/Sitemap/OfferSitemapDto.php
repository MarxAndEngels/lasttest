<?php

declare(strict_types=1);

namespace App\Dto\Sitemap;

use App\Complex\Dto\Dto;
use Carbon\Carbon;

class OfferSitemapDto extends Dto
{
  public int $id;
  public int $external_id;
  public string $category_enum;
  public MarkSitemapDto $mark;
  public FolderSitemapDto $folder;
  public ?GenerationSitemapDto $generation;


  protected function getUrl(string $siteUrl, string $categoryUrl, bool $urlWithGeneration): string
  {
    $url = "{$siteUrl}/{$categoryUrl}/{$this->mark->slug}/{$this->folder->slug}";
    if ($this->generation && $urlWithGeneration) {
      return "{$url}/{$this->generation->slug}/{$this->external_id}";
    } else {
      return "{$url}/{$this->external_id}";
    }
  }

  public function createSitemapUrl(string $siteUrl, array $categoryAssociation, bool $urlWithGeneration = false): array
  {
    $categoryUrl = $categoryAssociation[$this->category_enum];
    return [
      'loc' => $this->getUrl($siteUrl, $categoryUrl, $urlWithGeneration),
      'lastmod' => Carbon::now()->startOfDay()->subDays(2)->toRfc3339String(),
      'changefreq' => 'weekly',
      'priority' => '0.8'
    ];
  }

}
