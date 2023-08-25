<?php

namespace App\Models;

use App\Complex\Eloquent\ReferenceModel;
use App\Constants\Attributes\AttributeName;
use App\Constants\MediaConstants;
use App\Constants\TableConstants;
use App\QueryBuilders\SlideQueryBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Slide extends ReferenceModel implements HasMedia, Sortable
{
  use SortableTrait;
  use InteractsWithMedia;

  public $timestamps = true;


  protected string $builder = SlideQueryBuilder::class;

  public function newEloquentBuilder($query): SlideQueryBuilder
  {
    return new SlideQueryBuilder($query);
  }

  public array $sortable = [
    'order_column_name' => AttributeName::ORDER_COLUMN,
    'sort_when_creating' => true,
  ];


  public function sites(): BelongsToMany
  {
    return $this->belongsToMany(Site::class, TableConstants::SLIDE_SITE);
  }

  public function getImageAttribute(): ?array
  {
    $collectionName = MediaConstants::MEDIA_SLIDES;
    $media = $this->getFirstMedia($collectionName);
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

  public function getImageElementAttribute(): ?array
  {
    $collectionName = MediaConstants::MEDIA_SLIDE_ELEMENTS;
    $media = $this->getFirstMedia($collectionName);
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

  public function registerMediaCollections(): void
  {
    $this->addMediaCollection(MediaConstants::MEDIA_SLIDES)->singleFile();
    $this->addMediaCollection(MediaConstants::MEDIA_SLIDE_ELEMENTS)->singleFile();
  }

  /**
   * @throws \Spatie\Image\Exceptions\InvalidManipulation
   */
  public function registerMediaConversions(Media $media = null): void
  {
    $collection = MediaConstants::MEDIA_SLIDES;

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


    $collection = MediaConstants::MEDIA_SLIDE_ELEMENTS;

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
