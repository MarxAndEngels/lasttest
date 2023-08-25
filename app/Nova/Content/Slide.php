<?php

namespace App\Nova\Content;

use App\Constants\Attributes\AttributeName;
use App\Constants\MediaConstants;
use App\Nova\Crm\Site;
use App\Nova\Resource;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class Slide extends Resource
{
  use HasSortableRows;
  public static string $model = \App\Models\Slide::class;
  public static $title = AttributeName::TITLE;
  public static $search = [
    AttributeName::TITLE
  ];
  public static $with = ['media'];
  public function fields(NovaRequest $request): array
  {
    return [
      ID::make()->sortable(),

      Text::make(AttributeName::TITLE)
        ->sortable()
        ->rules('required', 'max:100'),

      Text::make(AttributeName::BODY)
        ->nullable()
        ->hideFromIndex()
        ->sortable(),

      Text::make(AttributeName::LINK)
        ->sortable()
        ->hideFromIndex()
        ->rules('required', 'max:100'),

      Images::make('Image', MediaConstants::MEDIA_SLIDES)
        ->conversionOnIndexView(MediaConstants::CONVERSION_SLIDE_1X)
        ->rules('required'),

      Images::make('Image Element', MediaConstants::MEDIA_SLIDE_ELEMENTS)
        ->conversionOnIndexView(MediaConstants::CONVERSION_THUMB)
        ->hideFromIndex(),

      Boolean::make(AttributeName::IS_ACTIVE)->default(true),


      BelongsToMany::make('site', 'sites', Site::class),



    ];
  }
}
