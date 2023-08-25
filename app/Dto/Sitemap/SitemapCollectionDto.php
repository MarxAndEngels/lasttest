<?php

declare(strict_types=1);

namespace App\Dto\Sitemap;


use App\Models\Offer;
use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObjectCollection;

class SitemapCollectionDto extends DataTransferObjectCollection
{

  public static function getSitemapPages(array $pages, array $categoryAssociation, string $siteUrl) : array
  {
    $pagesCollection = collect($pages)->map(fn($item) => ['slug' => $item === 'home' ? '': $item])->values();
    collect($categoryAssociation)->map(fn($item) => $pagesCollection->push(['slug' => $item]));
    $pageSitemapDto = new static(PageSitemapDto::arrayOf($pagesCollection->toArray()));
    return collect($pageSitemapDto)->map(fn(PageSitemapDto $dto) => $dto->createSitemapUrl($siteUrl))->all();
  }

  public static function getSitemapCatalog(Collection $offerCollection, string $siteUrl, string $categoryUrl, array $categoryAssociation, bool $urlWithGeneration = false):array
  {
    $generationCollection = $offerCollection->groupBy('generation_id')->map(fn($collection, $key) =>
    $collection->unique('generation_id')->map(fn($item) => [
      'id' => $item['generation']['id'],
      'name' => $item['generation']['name'],
      'slug' => $item['generation']['slug'],
      'mark' => $item['mark'],
      'folder' => $item['folder'],
      'folder_id' => $item['folder_id'],
      'category_enum' => $item['category_enum']
    ],
    )->first(),
    )->values();
    $markArray = $generationCollection->pluck('mark')->unique('id')->values()->all();
    $folderArray = $generationCollection->groupBy('folder_id')->map(fn($collection, $key) =>
        $collection->unique('folder_id')->map(fn($item) => [
          'id' => $item['folder']['id'],
          'title' => $item['folder']['title'],
          'slug' => $item['folder']['slug'],
          'mark' => $item['mark'],
          'category_enum' => $item['category_enum']
        ],
      )->first(),
    )->values()->all();

    $generationArray = $generationCollection->toArray();

    $markSiteMapDto = new static(MarkSitemapDto::arrayOf($markArray));
    $markSitemap = collect($markSiteMapDto)->map(fn(MarkSitemapDto $dto) => $dto->createSitemapUrl($siteUrl, $categoryUrl))->all();

    $folderSiteMapDto = new static(FolderSitemapDto::arrayOf($folderArray));
    $folderSitemap = collect($folderSiteMapDto)->map(fn(FolderSitemapDto $dto) => $dto->createSitemapUrl($siteUrl, $categoryUrl))->all();

    $generationSiteMapDto = new static(GenerationSitemapDto::arrayOf($generationArray));
    $generationSitemap = collect($generationSiteMapDto)->map(fn(GenerationSitemapDto $dto) => $dto->createSitemapUrl($siteUrl, $categoryAssociation))->all();

    $offerSiteMapDto = new static(OfferSitemapDto::arrayOf($offerCollection->toArray()));
    $offerSitemap = collect($offerSiteMapDto)->map(fn(OfferSitemapDto $dto) => $dto->createSitemapUrl($siteUrl, $categoryAssociation, $urlWithGeneration))->all();

    return array_merge($markSitemap, $folderSitemap, $generationSitemap, $offerSitemap);
  }
}
