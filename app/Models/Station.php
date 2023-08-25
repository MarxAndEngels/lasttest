<?php

namespace App\Models;

use App\Complex\Eloquent\ReferenceModel;
use App\Constants\Attributes\AttributeName;
use App\Constants\MediaConstants;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Station extends ReferenceModel implements HasMedia, Sortable
{
  use SortableTrait;
  use InteractsWithMedia;
  public $timestamps = true;

  public array $sortable = [
    'order_column_name' => AttributeName::ORDER_COLUMN,
    'sort_when_creating' => true,
    'sort_on_has_many' => true,
  ];
  public function stationCategory(): BelongsTo
  {
    return $this->belongsTo(Station::class);
  }

  public function registerMediaCollections(): void
  {
    $this->addMediaCollection(MediaConstants::MEDIA_STATIONS)
      ->singleFile();
  }

  /**
   * @throws \Spatie\Image\Exceptions\InvalidManipulation
   */
  public function registerMediaConversions(Media $media = null): void
  {
    $collection = MediaConstants::MEDIA_STATIONS;

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
