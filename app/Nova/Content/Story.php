<?php

namespace App\Nova\Content;

use App\Constants\Attributes\AttributeName;
use App\Constants\MediaConstants;
use App\Nova\Crm\Site;
use App\Nova\Resource;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class Story extends Resource
{
  use HasSortableRows;
  public static string $model = \App\Models\Story::class;
  public static $title = AttributeName::TITLE;
  public static $search = [
    AttributeName::ID, AttributeName::TITLE
  ];
  public static $with = ['media'];
  public function fields(NovaRequest $request): array
  {
    return [
      ID::make()->sortable(),

      Text::make(AttributeName::TITLE)
        ->sortable()
        ->rules('required', 'max:180'),

      Images::make('Image', MediaConstants::MEDIA_STORIES)
        ->conversionOnIndexView(MediaConstants::CONVERSION_TINY)
        ->rules('required'),

      Boolean::make(AttributeName::IS_ACTIVE),
      BelongsToMany::make('site', 'sites', Site::class),

      HasMany::make('stories', 'stories', StoryContent::class),


    ];
  }
}
