<?php

namespace App\Nova\Content;

use App\Constants\Attributes\AttributeName;
use App\Constants\TableConstants;
use App\Nova\Crm\Site;
use App\Nova\Resource;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class StationCategory extends Resource
{
  use HasSortableRows;
  public static string $model = \App\Models\StationCategory::class;
  public static $title = AttributeName::TITLE;
  public static $search = [
    AttributeName::ID, AttributeName::TITLE
  ];
  public static $with = ['site'];

  public static function label(): string
  {
    return 'Stations';
  }

  public function fields(NovaRequest $request): array
  {
    return [
      ID::make()->sortable(),

      BelongsTo::make('site', 'site', Site::class)->default(fn() => 21),

      Text::make(AttributeName::TITLE)
        ->sortable()
        ->rules('required', 'max:180'),


      Boolean::make(AttributeName::IS_ACTIVE),

      HasMany::make(TableConstants::STATIONS, TableConstants::STATIONS, Station::class)
    ];
  }
}
