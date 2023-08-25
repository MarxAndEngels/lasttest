<?php

namespace App\Models;

use App\Constants\Attributes\AttributeName;
use App\Constants\MediaConstants;
use App\Constants\TableConstants;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Dealer extends Model implements HasMedia
{
  protected $guarded = [];
  use InteractsWithMedia;

  protected $appends = [AttributeName::IMAGES, AttributeName::IMAGE_LOGO];

  public function getImageLogoAttribute(): ?string
  {
    return $this->getFirstMedia(MediaConstants::MEDIA_DEALER_LOGO)?->getUrl();
  }
  public function site(): HasMany
  {
    return $this->hasMany(Site::class);
  }
  public function getImagesAttribute(): ?array
  {
    $collectionName = MediaConstants::MEDIA_DEALERS;
    $mediaCollection = $this->getMedia($collectionName);
    $images = collect();
    if(!$mediaCollection){
      return null;
    }
    $mediaCollection->each(function (Media $media) use($collectionName, $images){
      $outputImages = collect(MediaConstants::CONVERSION_COLLECTION[$collectionName])->mapWithKeys(fn($item) => [
        $item => $media->getUrl($item)
      ])->all();
      $outputImagesWebp = collect(MediaConstants::CONVERSION_COLLECTION_WEBP[$collectionName])->mapWithKeys(fn($item) => [
        $item => $media->getUrl($item)
      ])->all();
      $outputImageSrc = [MediaConstants::CONVERSION_SRC => $media->getUrl()];
      $images->push(array_merge($outputImageSrc, $outputImages, $outputImagesWebp));
    });
   return $images->toArray();
  }
  public function sites() : BelongsToMany
  {
    return $this->belongsToMany(Site::class, TableConstants::DEALER_SITE, AttributeName::DEALER_ID, AttributeName::SITE_ID);
  }
  public function registerMediaCollections(): void
  {
    $this->addMediaCollection(MediaConstants::MEDIA_DEALERS);
    $this->addMediaCollection(MediaConstants::MEDIA_DEALER_LOGO)
      ->singleFile();
  }
  public function registerMediaConversions(Media $media = null): void
  {
    $collection = MediaConstants::MEDIA_DEALERS;
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

  }
}
