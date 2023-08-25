<?php

namespace App\Services;

use App\Constants\Attributes\AttributeName;
use App\Dto\Feeds\OfferFeedCollectionDto;
use App\Dto\Feeds\OfferFeedDto;
use App\Dto\YandexYmlFeed\YandexYmlCatalogFeedDto;
use App\Helpers\ArrayToXml;
use App\Models\FeedFilter;
use App\Models\Offer;
use App\Models\Site;
use Carbon\Carbon;

class FeedService
{
  protected ?Site $site = null;

  public function createFeedYandexXml(string $slug): string
  {
    $this->site = $this->getSite($slug);
    $this->site ?: abort(404);
    $filter[AttributeName::SITE_ID] = [
      'id' => $this->site->id,
      'onlyActive' => true
    ];
    return ArrayToXml::ArrayToYandexFeedXml(
      $this->getOffersYandexXml($filter)
    );
  }

  public function createFeedGoogleXml(string $slug): string
  {
    $this->site = $this->getSite($slug);
    $this->site ?: abort(404);
    $filter[AttributeName::SITE_ID] = [
      'id' => $this->site->id,
      'onlyActive' => true
    ];
    $siteTitle = $this->site->title;
    $siteUrl = $this->site->url;
    $data = [
      'chanel' =>
        [
          'title' => $siteTitle,
          'link' => $siteUrl,
          'description' => "Автосалон {$siteTitle} предлагает лучшие подержанные автомобили!",
          'item' => $this->getOffersGoogleXml($filter)
        ]
    ];
    return ArrayToXml::ArrayToGoogleFeedXml($data);
  }

  public function createFeedYandexXmlFilter(string $siteSlug, string $filterSlug): string
  {
    $feedFilter = $this->getFeedFilter($siteSlug, $filterSlug);
    $filter = $feedFilter['filter'];
    $this->site = $feedFilter['feedFilter']->site;
    return ArrayToXml::ArrayToYandexFeedXml(
      $this->getOffersYandexXml($filter)
    );
  }

  public function createFeedGoogleXmlFilter(string $siteSlug, string $filterSlug): string
  {
    $feedFilter = $this->getFeedFilter($siteSlug, $filterSlug);
    $filter = $feedFilter['filter'];
    $this->site = $feedFilter['feedFilter']->site;
    $siteTitle = $this->site->title;
    $siteUrl = $this->site->url;
    $data = [
      'chanel' =>
        [
          'title' => $siteTitle,
          'link' => $siteUrl,
          'description' => "Автосалон {$siteTitle} предлагает лучшие подержанные автомобили!",
          'item' => $this->getOffersGoogleXml($filter)
        ]
    ];
    return ArrayToXml::ArrayToGoogleFeedXml($data);
  }

  public function createFeedVkXmlFilter(string $siteSlug, string $filterSlug): string
  {
    $feedFilter = $this->getFeedFilter($siteSlug, $filterSlug);
    $filter = $feedFilter['filter'];
    $this->site = $feedFilter['feedFilter']->site;
    $siteTitle = $this->site->title;
    $data = [
      'chanel' =>
        [
          'title' => $siteTitle,
          'item' => $this->getOffersVkXml($filter)
        ]
    ];
    return ArrayToXml::ArrayToGoogleFeedXml($data);
  }
  public function createFeedYandexYmlCatalogFilter(string $siteSlug, string $filterSlug): string
  {
    $feedFilter = $this->getFeedFilter($siteSlug, $filterSlug);
    $filter = $feedFilter['filter'];
    $this->site = $feedFilter['feedFilter']->site;
    return ArrayToXml::ArrayToYandexFeedYml(
      $this->getOffersYandexYmlCatalog($filter)
    );
  }
  public function createFeedYandexYmlFilter(string $siteSlug, string $filterSlug): string
  {
    $feedFilter = $this->getFeedFilter($siteSlug, $filterSlug);
    $filter = $feedFilter['filter'];
    $this->site = $feedFilter['feedFilter']->site;
    return ArrayToXml::ArrayToYandexFeedYml(
      $this->getOffersYandexYml($filter)
    );
  }


  protected function getSite(string $slug): ?Site
  {
    return Site::query()->whereSlug($slug)->first();
  }

  protected function getMainSiteId(int $siteId): ?int
  {
    return Site::query()->select(AttributeName::ID)->getParentId($siteId)->first()?->id;
  }

  public function getFeedFilter(string $siteSlug, string $filterSlug): ?array
  {
    $feedFilterQuery = FeedFilter::query();
    $feedFilter = $feedFilterQuery->where(AttributeName::NAME, '=', $filterSlug)
      ->whereHas('site', fn($query) => $query->whereSlug($siteSlug))
      ->with('site')
      ->firstOrFail();
    $filter = $feedFilter->filter;
    $filter[AttributeName::SITE_ID] = [
      'id' => $feedFilter->site->id,
      'onlyActive' => true
    ];
    $feedFilter->download_at = Carbon::now();
    $feedFilter->save();
    return ['feedFilter' => $feedFilter, 'filter' => $filter];
  }

  protected function getOffersYandexXml(array $filter): array
  {
    $offersCollection = collect();
    Offer::query()->filter($filter)->selectForYandexXmlFeed()->lazy()->each(fn(Offer $offer) => $offersCollection->push((new OfferFeedDto($offer->toArray()))
      ->getOfferArrayForYandexFeedXml($this->site->url, $this->site->category_url, $this->site->generation_url, $this->site->category_association))
    );
    return $offersCollection->all();
  }

  protected function getOffersGoogleXml(array $filter): array
  {
    $offersCollection = collect();
    Offer::query()->filter($filter)->selectForGoogleXmlFeed()->lazy()->each(fn(Offer $offer) => $offersCollection->push((new OfferFeedDto($offer->toArray()))
      ->getOfferArrayForGoogleFeedXml($this->site->url, $this->site->category_url, $this->site->generation_url, $this->site->category_association))
    );
    return $offersCollection->all();
  }

  public function getOffersVkXml(array $filter): array
  {
    $offersCollection = collect();
    Offer::query()->filter($filter)->selectForVkXmlFeed()->lazy()->each(fn(Offer $offer) => $offersCollection->push((new OfferFeedDto($offer->toArray()))
      ->getOfferArrayForVkFeedXml($this->site->url, $this->site->category_url, $this->site->generation_url, $this->site->category_association))
    );
    return $offersCollection->all();
  }

  public function getOffersYandexYml(array $filter): array
  {
    $offersCollection = collect();
    Offer::query()
      ->filter($filter)
      ->selectForYandexYmlFeed()
      ->lazy()
      ->each(fn(Offer $offer) =>
      $offersCollection->push($offer->toArray())
      );
    $offersArr = $offersCollection->all();
    return [
      'shop' => [
        'name' => $this->site->title,
        'company' => $this->site->legal_name ?: 'ООО',
        'url' => $this->site->url,
        'currencies' => [
          'currency' => [
            '_attributes' => ['id' => 'RUR', 'rate' => 1]
          ]
        ],
        'categories' => $this->getCategoriesYandexYmlCatalog(),
        'offers' => [
          'offer' =>
              OfferFeedCollectionDto::getOffersArrayForYandexYml($offersArr, $this->site->url, $this->site->category_url, $this->site->generation_url, $this->site->category_association)
        ]
      ]
    ];
  }

  protected function getOffersYandexYmlCatalog(array $filter): array
  {
    $offersCollection = collect();
    Offer::query()
      ->filter($filter)->selectForYandexYmlCatalogFeed()
      ->lazy()
      ->each(fn(Offer $offer) =>
          $offersCollection->push($offer->toArray())
      );
    $offersCollection = $offersCollection->groupBy('folder_id')
                        ->filter(fn($collection) => $collection->count() >= 4)
                        ->map(fn($collection) => $collection->values())->flatten(1)->values();
    $offersArr = $offersCollection->all();


    $markArray = $offersCollection->groupBy('mark_id')->map(fn($collection, $key) => [
      'mark' => $collection->unique('mark_id')->map(fn($item) => [
        'id' => $item['mark']['id'],
        'title' => $item['mark']['title'],
        'slug' => $item['mark']['slug'],
        'image' => $item['images'][0]['src']
      ])->first(),
      'total' => $collection->count('id'),
      'min_price' => $collection->min('price')
    ])->all();
    $folderArray = $offersCollection->groupBy('folder_id')->map(fn($collection, $key) => [
      'folder' => $collection->unique('folder_id')->map(fn($item) => [
        'mark' => $item['mark'],
        'id' => $item['folder']['id'],
        'title' => $item['folder']['title'],
        'slug' => $item['folder']['slug'],
        'image' => $item['images'][0]['src']
      ]
      )->first(),
      'total' => $collection->count('id'),
      'min_price' => $collection->min('price')
    ])->all();
    return [
      'shop' => [
        'name' => $this->site->title,
        'company' => $this->site->legal_name ?: 'ООО',
        'url' => $this->site->url,
        'currencies' => [
          'currency' => [
            '_attributes' => ['id' => 'RUR', 'rate' => 1]
          ]
        ],
        'categories' => $this->getCategoriesYandexYmlCatalog(),
        'sets' => ['set' => $this->getSetYandexYmlCatalog($markArray, $folderArray)],
        'offers' => [
          'offer' =>
            array_merge(
              $this->getSetOfferYandexYmlCatalog($markArray, $folderArray),
              OfferFeedCollectionDto::getOffersArrayForYandexYmlCatalog($offersArr, $this->site->url, $this->site->category_url, $this->site->generation_url, $this->site->category_association)
            )
        ]
      ]
    ];
  }

  protected function getSetYandexYmlCatalog(array $marks, array $folders): array
  {
    $city = $this->site->city ?: 'в Москве';
    $setCategory = [
      [
        '_attributes' => ['id' => "sc-1"],
        'name' => "Автомобили с пробегом {$city}",
        'url' => "{$this->site->url}/{$this->site->category_url}"
      ]
    ];
    $setMark = collect($marks)->map(fn($item) => (new YandexYmlCatalogFeedDto($item))->getSetMark($this->site->url, $this->site->category_url, $this->site->city))->all();
    $setFolder = collect($folders)->map(fn($item) => (new YandexYmlCatalogFeedDto($item))->getSetFolder($this->site->url, $this->site->category_url, $this->site->city))->all();
    return array_merge($setCategory, $setMark, $setFolder);
  }

  protected function getSetOfferYandexYmlCatalog(array $mark, array $folder): array
  {
    $offerSetMark = collect($mark)->map(fn($item) => (new YandexYmlCatalogFeedDto($item))->getSetMarkOffer($this->site->url, $this->site->category_url))->all();
    $offerSetFolder = collect($folder)->map(fn($item) => (new YandexYmlCatalogFeedDto($item))->getSetFolderOffer($this->site->url, $this->site->category_url))->all();
    return array_merge($offerSetMark, $offerSetFolder);
  }

  protected function getCategoriesYandexYmlCatalog(): array
  {
    return [
      'category' => [
        '_attributes' => ['id' => 1],
        '_value' => 'Легковой автомобиль'
      ]
    ];
  }

  protected function getCategoriesYandexYml(): array
  {
    return [
      'category' => [
        '_attributes' => ['id' => 1],
        '_value' => 'Легковой автомобиль'
      ]
    ];
  }
}
