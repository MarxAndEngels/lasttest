<?php

namespace App\Nova\Content;

use App\Constants\Attributes\AttributeName;
use App\Nova\Resource;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Mark extends Resource
{
  public static string $model = \App\Models\Mark::class;
  public static $perPageOptions = [50, 100, 150];

  public static $title = AttributeName::TITLE;

  public static function searchableColumns(): array
  {
    return [AttributeName::TITLE];
  }

  public function fields(NovaRequest $request): array
  {
    return [
      ID::make()->sortable(),
      Text::make(AttributeName::TITLE)
        ->sortable()
        ->rules('max:180'),

      Text::make(AttributeName::TITLE_RUS)
        ->sortable()
        ->rules('max:180'),

      Slug::make(AttributeName::SLUG)
        ->from(AttributeName::TITLE)
        ->hideFromIndex()
        ->showOnPreview(),

      Text::make(AttributeName::ORDER_COLUMN)
        ->sortable(),

      MorphMany::make('siteTexts'),

      HasMany::make('Folders', 'folders', Folder::class),
    ];
  }
}
