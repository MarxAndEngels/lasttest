<?php

namespace App\Models;

use App\Complex\Eloquent\ReferenceModel;
use App\Constants\Attributes\AttributeName;
use App\Constants\MediaConstants;
use App\QueryBuilders\BankQueryBuilder;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use \Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Bank extends ReferenceModel implements HasMedia
{
  use InteractsWithMedia;

  protected $appends = [AttributeName::IMAGE, AttributeName::IMAGE_CAR, AttributeName::LICENSE_FILE];
  protected string $builder = BankQueryBuilder::class;

  public function siteTexts(): MorphMany
  {
    return $this->morphMany(SiteText::class, 'model');
  }
  public function siteText(): MorphOne
  {
    return $this->morphOne(SiteText::class, 'model');
  }

  public function newEloquentBuilder($query): BankQueryBuilder
  {
    return new BankQueryBuilder($query);
  }

  public function getImageAttribute(): ?string
  {
    $media = $this->getFirstMedia(MediaConstants::MEDIA_BANKS);
    return $media?->getUrl();
  }

  public function getImageCarAttribute(): ?array
  {
    $collectionName = MediaConstants::MEDIA_BANKS_CAR;
    $media = $this->getFirstMedia(MediaConstants::MEDIA_BANKS_CAR);
    if (!$media) {
      return null;
    }
    $outputImages = collect(MediaConstants::CONVERSION_COLLECTION[$collectionName])->mapWithKeys(fn($item) => [
      $item => $media->getUrl($item)
    ])->all();
    $outputImagesWebp = collect(MediaConstants::CONVERSION_COLLECTION_WEBP[$collectionName])->mapWithKeys(fn($item) => [
      $item => $media->getUrl($item)
    ])->all();
    $outputImageSrc = [MediaConstants::CONVERSION_SRC => $media->getUrl()];

    return array_merge($outputImageSrc, $outputImages, $outputImagesWebp);
  }
  public function getLicenseFileAttribute(): ?string
  {
    $media = $this->getFirstMedia(MediaConstants::MEDIA_BANKS_LICENSE);
    return $media?->getUrl();
  }

  public function registerMediaCollections(): void
  {
    $this->addMediaCollection(MediaConstants::MEDIA_BANKS)->singleFile();
    $this->addMediaCollection(MediaConstants::MEDIA_BANKS_CAR)->singleFile();
    $this->addMediaCollection(MediaConstants::MEDIA_BANKS_LICENSE)->singleFile();
  }

  /**
   * @throws \Spatie\Image\Exceptions\InvalidManipulation
   */
  public function registerMediaConversions(Media $media = null): void
  {
    $collection = MediaConstants::MEDIA_BANKS_CAR;

    collect(MediaConstants::CONVERSION_COLLECTION[$collection])->each(fn($conversion) => $this
      ->addMediaConversion($conversion)
      ->performOnCollections($collection)
      ->keepOriginalImageFormat()
      ->width(MediaConstants::WIDTH[$conversion])
    );

    #WEBP
    collect(MediaConstants::CONVERSION_COLLECTION_WEBP[$collection])->each(fn($conversion) => $this
      ->addMediaConversion($conversion)
      ->performOnCollections($collection)
      ->width(MediaConstants::WIDTH[$conversion])
      ->format(MediaConstants::FORMAT_WEBP)
    );

  }
}
