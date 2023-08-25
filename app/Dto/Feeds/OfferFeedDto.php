<?php
declare(strict_types=1);

namespace App\Dto\Feeds;

use App\Complex\Dto\Dto;
use App\Dto\Filter\TitleIdSlugDto;
use App\Dto\NameDto;
use App\Dto\NameSlugDto;
use App\Dto\OwnerDto;
use App\Dto\PlexCrm\NameTitleDto;
use App\Dto\PlexCrm\NameTitleTitleShortDto;
use App\Helpers\Modifiers;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class OfferFeedDto extends Dto
{
  public ?int $id;
  public int $external_id;
  public TitleIdSlugDto $mark;
  public TitleIdSlugDto $folder;
  public ?NameDto $modification;
  public ?NameTitleTitleShortDto $body_type;
  public ?NameTitleDto $drive_type;
  public ?NameTitleDto $gearbox;
  public ?NameTitleDto $engine_type;
  public ?NameTitleDto $wheel;
  public ?NameTitleDto $color;
  public ?NameSlugDto $generation;
  public ?OwnerDto $owner;
  public ?TitleIdSlugDto $dealer;
  public ?array $rating;
  public ?string $name;
  public ?string $vin;
  public int $year;
  public ?int $engine_power;
  public ?float $engine_volume;
  public ?int $run;
  public ?array $images;
  public ?float $price;
  public ?int $is_active;
  public ?int $count;
  public ?string $type_enum;
  public ?string $category_enum;

  private function getOwners() :string
  {
    if (!$this->owner){
      return 'четыре и более';
    }
    return match ($this->owner->number){
      0 => 'не было владельцев',
      1 => 'один владелец',
      2 => 'два владельца',
      3 => 'три владельца',
      default => 'четыре и более',
    };
  }
  public function getOfferArrayForYandexFeedXml(string $siteUrl, string $categoryUrl, bool $urlWithGeneration, array $categoryAssociation): array
  {
    $image = reset($this->images);

    if (!$image) {
      return [];
    }

    $images = collect($image)
      ->only(['original', 'medium', 'small'])
      ->values()
      ->toArray();

    return [
      'unique_id' => $this->external_id,
      'mark_id' => $this->mark->title,
      'folder_id' => $this->generation?->name ? "{$this->folder->title}, {$this->generation->name}": $this->folder->title,
      'modification_id' => $this->modification?->name ?? "{$this->engine_volume} ({$this->engine_power} л.с.)",
      'url' => $this->getUrl($siteUrl, $categoryUrl, $urlWithGeneration, $categoryAssociation),
      'body_type' => $this->body_type->title_short ?: $this->body_type->title,
      'color' => $this->color->title ?? "",
      'availability' => "в наличии",
      'custom' => "растаможен",
      'state' => "отличное",
      'owners_number' => $this->getOwners(),
      'run' => $this->run,
      'year' => $this->year,
      'price' => $this->price,
      'currency' => "RUB",
      'description' => "В наличии! {$this->mark->title} {$this->folder->title} с пробегом за {$this->price} руб.",
      'images' => [
        'image' => $images,
      ],
    ];
  }

  public function getOfferArrayForYandexFeedYmlCatalog(string $siteUrl, string $categoryUrl, bool $urlWithGeneration, array $categoryAssociation): array
  {
    $run = Modifiers::numberFormatPrice((float)$this->run);
    $offer = [
      '_attributes.id' => $this->external_id,
      'name' => "{$this->year}, {$run} км",
      'vendor' => $this->mark->title,
      'url' => $this->getUrl($siteUrl, $categoryUrl, $urlWithGeneration, $categoryAssociation) . '?utm_source=yandex&utm_medium=organic&utm_campaign=yml-catalog',
      'price' => $this->price,
      'currencyId' => 'RUR',
      'categoryId' => 1,
      'set-ids' => "sf-{$this->folder->id}",
      'picture' => collect($this->images)->pluck('medium')->first(),
      'description' => "{$this->mark->title} {$this->folder->title} {$this->modification?->name} {$this->generation?->name}",
      '__custom:param:1._attributes.name' => 'Год создания',
      '__custom:param:1._value' => (string)$this->year,
      '__custom:param:2._attributes.name' => 'Пробег',
      '__custom:param:2._value' => (string)$this->run,
      '__custom:param:3._attributes.name' => 'Двигатель, литры',
      '__custom:param:3._value' => (string)$this->engine_volume,
      '__custom:param:4._attributes.name' => 'Двигатель, л.с.',
      '__custom:param:4._value' => (string)$this->engine_power,
      '__custom:param:5._attributes.name' => 'Топливо',
      '__custom:param:5._value' => $this->engine_type->title,
      '__custom:param:6._attributes.name' => 'Коробка передач',
      '__custom:param:6._value' => $this->gearbox->title,
      '__custom:param:7._attributes.name' => 'Привод',
      '__custom:param:7._value' => $this->drive_type->title,
      '__custom:param:8._attributes.name' => 'Состояние',
      '__custom:param:8._value' => "Не требует ремонта",
      '__custom:param:9._attributes.name' => 'Конверсия',
      '__custom:param:9._value' => (string)$this->rating['rating_total'] ?? 4,
    ];
    return Arr::undot($offer);
  }

  public function getOfferArrayForYandexFeedYml(string $siteUrl, string $categoryUrl, bool $urlWithGeneration, array $categoryAssociation): array
  {
    $offer = [
      '_attributes.id' => $this->external_id,
      'name' => $this->name,
      'vendor' => $this->mark->title,
      'url' => $this->getUrl($siteUrl, $categoryUrl, $urlWithGeneration, $categoryAssociation),
      'price' => $this->price,
      'currencyId' => 'RUR',
      'categoryId' => 1,
      'picture' => collect($this->images)->pluck('original')->first(),
    ];
    return Arr::undot($offer);
  }

  public function getOfferArrayForGoogleFeedXml(string $siteUrl, string $categoryUrl, bool $urlWithGeneration, array $categoryAssociation): array
  {
    return [
      'g:id' => $this->external_id,
      'g:title' => "{$this->mark->title} {$this->folder->title} {$this->year} г.",
      'g:description' => "{$this->getAccessCredit()} руб/месяц",
      'g:link' => $this->getUrl($siteUrl, $categoryUrl, $urlWithGeneration, $categoryAssociation),
      'g:image_link' => collect($this->images)->pluck('medium')->first(),
      'g:price' => "{$this->price} RUB",
      'g:availability' => "in stock",
      'g:brand' => $this->mark->title
    ];
  }

  private function transformForVk(string $string): string
  {
    if(Str::upper($string) != $string)
    {
      return $string;
    }
    return Str::ucfirst($string);
  }
  public function getOfferArrayForVkFeedXml(string $siteUrl, string $categoryUrl, bool $urlWithGeneration, array $categoryAssociation): array
  {
    return [
      'id' => $this->external_id,
      'title' => "{$this->mark->title} {$this->folder->title}, {$this->generation?->name} {$this->year} г.",
      'link' => $this->getUrl($siteUrl, $categoryUrl, $urlWithGeneration, $categoryAssociation),
      'brand' => $this->transformForVk($this->mark->title),
      'image_link' => collect($this->images)->pluck('medium')->first(),
      'model' => $this->transformForVk($this->folder->title),
      'year' => $this->year,
      'mileage' => [
        'value' => $this->run,
        'unit' => 'KM'
      ],
      'price' => "{$this->price} RUB",
      'min_price' => "{$this->getAccessCredit()} RUB",
      'body_style' => $this->body_type?->title_short ?: $this->body_type?->title,
      'exterior_color' => $this->color?->title,
      'state_of_vehicle' => 'used',
      'condition' => 'отличное',
      'vehicle_type' => 'car_truck'
    ];
  }

  public function getOfferArrayLinkForPlexCrm(string $siteUrl, string $categoryUrl, int $siteExternalId, bool $urlWithGeneration, array $categoryAssociation): array
  {
    return [
      'id' => $this->external_id,
      'siteId' => $siteExternalId,
      'url' => $this->getUrl($siteUrl, $categoryUrl, $urlWithGeneration, $categoryAssociation),
      'isActive' => (bool)$this->is_active,
      'externalId' => $this->id,
    ];
  }

  public function getOfferArrayForTelegramChannel(string $siteUrl, string $categoryUrl, bool $urlWithGeneration, array $categoryAssociation): array
  {
    $images = array_map(fn($img) => $img['medium'], array_slice($this->images, 0, 10));

    return [
      'markTitle' => $this->mark->title,
      'folderTitle' => $this->folder->title,
      'generationName' => $this->generation->name,
      'modificationName' => $this->modification->name ?? "{$this->engine_volume} ({$this->engine_power} л.с.)",
      'driveTypeTitle' => $this->drive_type?->title ?? "",
      'bodyTypeTitle' => $this->body_type->title,
      'url' => $this->getUrl($siteUrl, $categoryUrl, $urlWithGeneration, $categoryAssociation) . '?utm_source=telegram&utm_medium=telegram&utm_campaign=telegram',
      'ownersNumber' => $this->owner->title,
      'run' => $this->run,
      'year' => $this->year,
      'price' => $this->price,
      'images' => array_values($images)
    ];
  }

  public function getOfferArrayForReportCsv(string $siteUrl, string $categoryUrl, bool $urlWithGeneration, array $categoryAssociation): array
  {
    return [
      $this->external_id,
      $siteUrl,
      $this->dealer?->title,
      $this->mark->title,
      $this->folder->title,
      "{$this->engine_volume} л.",
      "{$this->engine_power} л.с",
      $this->gearbox->title,
      $this->year,
      $this->price,
      $this->count,
      $this->is_active,
      $this->type_enum,
      $this->getUrl($siteUrl, $categoryUrl, $urlWithGeneration, $categoryAssociation),
    ];
  }

  private function getUrl($siteUrl, string $categoryUrl, bool $urlWithGeneration, array $categoryAssociation = null): string
  {
    $markSlug = $this->mark->slug;
    $folderSlug = $this->folder->slug;
    $genSlug = $this->generation ? "/{$this->generation->slug}" : '';
    $extId = "/{$this->external_id}";
    $catSlug = $categoryAssociation && $this->category_enum ? ($categoryAssociation[$this->category_enum] ?? '') : '';
    if ($catSlug) {
      $url = "{$siteUrl}/{$catSlug}/{$markSlug}/{$folderSlug}";
    } else {
      $url = "{$siteUrl}/{$categoryUrl}/{$markSlug}/{$folderSlug}";
    }
    if ($urlWithGeneration && $genSlug) {
      $url .= $genSlug;
    }
    return $url . $extId;
  }

  protected function getAccessCredit(): float
  {
    $creditPercent = 4.9;
    $creditPeriod = 84;
    $ratio = ($creditPercent / 12) / 100;
    $formula = ($ratio * (pow((1 + $ratio), $creditPeriod))) / ((pow((1 + $ratio), $creditPeriod) - 1));
    return round($formula * (int)$this->price);
  }
}
