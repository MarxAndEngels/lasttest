<?php

declare(strict_types=1);

namespace App\Dto\YandexYmlFeed;

use App\Complex\Dto\Dto;

class YandexYmlCatalogFeedDto extends Dto
{

  public ?MarkYmlDto $mark;
  public ?FolderYmlDto $folder;
  public float $min_price;
  public int $total;


  protected function generateUrl(string $siteUrl, string $categoryUrl): string
  {
    if($this->folder){
      $link = "{$siteUrl}/{$categoryUrl}/{$this->folder->mark->slug}/{$this->folder->slug}";
    }else{
      $link = "{$siteUrl}/{$categoryUrl}/{$this->mark->slug}";
    }
    return "{$link}?utm_source=yandex&utm_medium=organic&utm_campaign=yml-catalog";
  }

  public function getSetMark(string $siteUrl, string $categoryUrl, string $city = "в Москве"): array
  {
    return \Arr::undot([
      '_attributes.id' => "sm-{$this->mark->id}",
      'name' => "{$this->mark->title} с пробегом {$city}",
      'url' => $this->generateUrl($siteUrl, $categoryUrl)
    ]);
  }
  public function getSetFolder(string $siteUrl, string $categoryUrl, string $city = "в Москве"): array
  {
    return \Arr::undot([
      '_attributes.id' => "sf-{$this->folder->id}",
      'name' => "{$this->folder->mark->title} {$this->folder->title} с пробегом {$city}",
      'url' => $this->generateUrl($siteUrl, $categoryUrl)
    ]);
  }

  public function getSetMarkOffer(string $siteUrl, string $categoryUrl): array
  {
    $rating = ($this->total*100)/1000;
    $setMarkOfferArray = [
      '_attributes' => ['id' => "smo-{$this->mark->id}"],
      'name' => $this->mark->title,
      'vendor' => $this->mark->title,
      'url' =>  $this->generateUrl($siteUrl, $categoryUrl),
      'price._attributes.from' => 'true',
      'price._value' => (string)$this->min_price,
      'currencyId' => 'RUR',
      'categoryId' => 1,
      'set-ids' => "sc-1",
      'picture' => $this->mark->image,
      'description' => "{$this->mark->title}",
      '__custom:param:1._attributes.name' => 'Число объявлений',
      '__custom:param:1._value' => (string)$this->total,
      '__custom:param:2._attributes.name' => 'Число объявлений во всех регионах',
      '__custom:param:2._value' => (string)$this->total,
      '__custom:param:3._attributes.name' => 'Конверсия',
      '__custom:param:3._value' => (string)$rating
    ];
    return \Arr::undot($setMarkOfferArray);
  }

  public function getSetFolderOffer(string $siteUrl, string $categoryUrl): array
  {
    $rating = ($this->total*100)/1000;
    $setFolderOfferArray = [
      '_attributes' => ['id' => "sfo-{$this->folder->mark->id}"],
      'name' => $this->folder->title,
      'vendor' => $this->folder->mark->title,
      'url' => $this->generateUrl($siteUrl, $categoryUrl),
      'price._attributes.from' => 'true',
      'price._value' => (string)$this->min_price,
      'currencyId' => 'RUR',
      'picture' => $this->folder->image,
      'categoryId' => 1,
      'set-ids' => "sm-{$this->folder->mark->id}",
      'description' => "{$this->folder->mark->title} {$this->folder->title}",
      '__custom:param:1._attributes.name' => 'Число объявлений',
      '__custom:param:1._value' => (string)$this->total,
      '__custom:param:2._attributes.name' => 'Число объявлений во всех регионах',
      '__custom:param:2._value' => (string)$this->total,
      '__custom:param:3._attributes.name' => 'Конверсия',
      '__custom:param:3._value' => (string)$rating
    ];
    return \Arr::undot($setFolderOfferArray);
  }
}
