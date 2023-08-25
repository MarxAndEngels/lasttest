<?php

namespace App\Models;

use App\Complex\Eloquent\ReferenceModel;
use App\Constants\Attributes\AttributeName;
use App\Constants\MediaConstants;
use App\Constants\TableConstants;
use App\QueryBuilders\StoryQueryBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Story extends ReferenceModel implements HasMedia, Sortable
{
  use SortableTrait;
  use InteractsWithMedia;
  public $timestamps = true;

  protected string $builder = StoryQueryBuilder::class;

  public array $sortable = [
    'order_column_name' => AttributeName::ORDER_COLUMN,
    'sort_when_creating' => true,
  ];

  public function newEloquentBuilder($query): StoryQueryBuilder
  {
    return new StoryQueryBuilder($query);
  }

  public function stories(): HasMany
  {
    return $this->hasMany(StoryContent::class, AttributeName::STORY_ID, AttributeName::ID);
  }

  public function sites(): BelongsToMany
  {
    return $this->belongsToMany(Site::class, TableConstants::STORY_SITE);
  }
  public function registerMediaCollections(): void
  {
    $this->addMediaCollection(MediaConstants::MEDIA_STORIES)->singleFile();
  }
  /**
   * @throws \Spatie\Image\Exceptions\InvalidManipulation
   */
  public function registerMediaConversions(Media $media = null): void
  {
    $collection = MediaConstants::MEDIA_STORIES;

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
