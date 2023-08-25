<?php

namespace App\Nova\Content;

use App\Constants\Attributes\AttributeName;
use App\Constants\MediaConstants;
use App\Nova\Resource;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class StoryContent extends Resource
{
  use HasSortableRows;

  public static string $model = \App\Models\StoryContent::class;
  public static $title = AttributeName::TITLE;
  public static $search = [
    AttributeName::ID, AttributeName::TITLE
  ];
  public function fields(NovaRequest $request): array
  {
    return [
      ID::make()->sortable(),

      BelongsTo::make('story'),

      Text::make(AttributeName::TITLE)
        ->sortable()
        ->rules('required', 'max:180'),


      Images::make('Image', MediaConstants::MEDIA_STORY_CONTENTS)
        ->conversionOnIndexView(MediaConstants::CONVERSION_MEDIUM)
        ->rules('required'),

      Textarea::make(AttributeName::BODY)
        ->rules('required')
        ->hideFromIndex(),

      Text::make(AttributeName::BUTTON_TITLE)
        ->hideFromIndex()
        ->rules('required','max:30'),

      Text::make(AttributeName::BUTTON_LINK)
        ->hideFromIndex()
        ->rules('required', 'max:30'),

      Text::make(AttributeName::BUTTON_COLOR)
        ->hideFromIndex()
        ->rules('required', 'max:30'),

      Boolean::make(AttributeName::IS_ACTIVE),
    ];
  }
}
