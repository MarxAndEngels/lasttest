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

class Station extends Resource
{
  use HasSortableRows;
  public static string $model = \App\Models\Station::class;
  public static $title = AttributeName::TITLE;

  public static $search = [
    AttributeName::ID, AttributeName::TITLE
  ];

  public function fields(NovaRequest $request): array
  {
    return [
      ID::make()->sortable(),

      BelongsTo::make('stationCategory'),

      Text::make(AttributeName::TITLE)
        ->sortable()
        ->rules('required', 'max:180'),

      Text::make(AttributeName::PRICE)
        ->rules('required', 'max:30'),

      Textarea::make(AttributeName::BODY)
        ->hideFromIndex(),

      Images::make('Image', MediaConstants::MEDIA_STATIONS)
        ->conversionOnIndexView(MediaConstants::CONVERSION_LARGE)
        ->rules('required'),

      Boolean::make(AttributeName::IS_ACTIVE),
    ];
  }
}
