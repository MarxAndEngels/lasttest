<?php

namespace App\Services;

use App\Constants\Attributes\AttributeName;
use App\Dto\Sitemap\SitemapCollectionDto;
use App\Helpers\ArrayToXml;
use App\Models\Offer;
use App\Models\Site;

class SitemapService
{
  public function createSitemap(string $siteSlug): string
  {
    $site = Site::query()->whereSlug($siteSlug)->firstOrFail();

    $pages = $site->route_pages;

    $offersCollection = collect();
    $filter[AttributeName::SITE_ID] = [
      'id' => $site->id,
      'onlyActive' => true
    ];

    Offer::query()->filter($filter)->selectForSitemap()->whereHas('generation')->lazy()
      ->each(fn(Offer $offer) =>
        $offersCollection->push($offer->toArray())
    );

    $data = SitemapCollectionDto::getSitemapCatalog($offersCollection, $site->url, $site->category_url, $site->category_association, $site->generation_url);
    if($pages && isset($pages['pages'])) {
      $sitemapPages = SitemapCollectionDto::getSitemapPages($pages['pages'], $site->category_association, $site->url);
      $data = array_merge($sitemapPages, $data);
    }

    return ArrayToXml::ArrayToSitemapXml([
        'url' => $data
      ]);

  }
}
