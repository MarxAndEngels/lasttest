<?php

namespace App\Models;

use App\Complex\Eloquent\ReferenceModel;
use App\Constants\Attributes\AttributeName;
use App\Constants\MediaConstants;
use App\Constants\TableConstants;
use App\QueryBuilders\ArticleQueryBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;


class Article extends ReferenceModel implements HasMedia
{
  use InteractsWithMedia;
  use HasEagerLimit;

  protected $appends = [AttributeName::IMAGE, AttributeName::IMAGE_PREVIEW, AttributeName::IMAGE_SLIDE];
  public $timestamps = true;
  public $casts = [
    AttributeName::PUBLISHED_AT => 'datetime',
    AttributeName::CREATED_AT => 'datetime',
    AttributeName::UPDATED_AT => 'datetime',
  ];
  protected string $builder = ArticleQueryBuilder::class;

  public function newEloquentBuilder($query): ArticleQueryBuilder
  {
    return new ArticleQueryBuilder($query);
  }

  public function category(): BelongsTo
  {
    return $this->belongsTo(ArticleCategory::class, AttributeName::ARTICLE_CATEGORY_ID, AttributeName::ID);
  }

  public function getImageAttribute(): ?array
  {
    $collectionName = MediaConstants::MEDIA_ARTICLES;
    $media = $this->getFirstMedia($collectionName);
    if(!$media){
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

  public function getImagePreviewAttribute(): ?array
  {
    $collectionName = MediaConstants::MEDIA_ARTICLE_PREVIEWS;
    $media = $this->getFirstMedia($collectionName);
    if(!$media){
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

  public function getImageSlideAttribute(): ?array
  {
    $collectionName = MediaConstants::MEDIA_ARTICLE_SLIDE;
    $mediaCollection = $this->getMedia($collectionName);
    $images = collect();
    if(!$mediaCollection){
      return null;
    }
    $mediaCollection->each(function ($media) use($collectionName, $images){
      $outputImages = collect(MediaConstants::CONVERSION_COLLECTION[$collectionName])->mapWithKeys(fn($item) => [
        $item => $media->getUrl($item)
      ])->all();
//      $outputImagesWebp = collect(MediaConstants::CONVERSION_COLLECTION_WEBP[$collectionName])->mapWithKeys(fn($item) => [
//        $item => $media->getUrl($item)
//      ])->all();
      $outputImageSrc = [MediaConstants::CONVERSION_SRC => $media->getUrl()];
      $images->push(array_merge($outputImageSrc, $outputImages));
    });
    return $images->toArray();
  }

  public function registerMediaCollections(): void
  {
    $this->addMediaCollection(MediaConstants::MEDIA_ARTICLES)->singleFile();
    $this->addMediaCollection(MediaConstants::MEDIA_ARTICLE_PREVIEWS)->singleFile();
    $this->addMediaCollection(MediaConstants::MEDIA_ARTICLE_SLIDE);
  }

  /**
   * @throws \Spatie\Image\Exceptions\InvalidManipulation
   */
  public function registerMediaConversions(Media $media = null): void
  {
    $collection = MediaConstants::MEDIA_ARTICLES;

    collect(MediaConstants::CONVERSION_COLLECTION[$collection])->each(fn($conversion) =>
    $this
      ->addMediaConversion($conversion)
      ->performOnCollections($collection)
      ->keepOriginalImageFormat()
      ->width(MediaConstants::WIDTH[$conversion])
    );

    #WEBP
    collect(MediaConstants::CONVERSION_COLLECTION_WEBP[$collection])->each(fn($conversion) =>
    $this
      ->addMediaConversion($conversion)
      ->performOnCollections($collection)
      ->width(MediaConstants::WIDTH[$conversion])
      ->format(MediaConstants::FORMAT_WEBP)
    );

    $collection = MediaConstants::MEDIA_ARTICLE_PREVIEWS;

    collect(MediaConstants::CONVERSION_COLLECTION[$collection])->each(fn($conversion) =>
    $this
      ->addMediaConversion($conversion)
      ->performOnCollections($collection)
      ->keepOriginalImageFormat()
      ->width(MediaConstants::WIDTH[$conversion])
    );

    #WEBP
    collect(MediaConstants::CONVERSION_COLLECTION_WEBP[$collection])->each(fn($conversion) =>
    $this
      ->addMediaConversion($conversion)
      ->performOnCollections($collection)
      ->width(MediaConstants::WIDTH[$conversion])
      ->format(MediaConstants::FORMAT_WEBP)
    );

    $collection = MediaConstants::MEDIA_ARTICLE_SLIDE;

    collect(MediaConstants::CONVERSION_COLLECTION[$collection])->each(fn($conversion) =>
    $this
      ->addMediaConversion($conversion)
      ->performOnCollections($collection)
      ->keepOriginalImageFormat()
      ->width(MediaConstants::WIDTH[$conversion])
    );

    #WEBP
//    collect(MediaConstants::CONVERSION_COLLECTION_WEBP[$collection])->each(fn($conversion) =>
//    $this
//      ->addMediaConversion($conversion)
//      ->performOnCollections($collection)
//      ->width(MediaConstants::WIDTH[$conversion])
//      ->format(MediaConstants::FORMAT_WEBP)
//    );
  }
}
