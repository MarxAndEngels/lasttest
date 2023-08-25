<?php

namespace App\Nova\Site;

use App\Constants\Attributes\AttributeName;
use App\Constants\Permission\RoleConstants;
use App\Nova\Resource;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Set extends Resource
{
  public static string $model = \App\Models\Set::class;
  public static $title = AttributeName::TITLE;
  public static $search = [
    AttributeName::ID, AttributeName::TITLE
  ];

  public function fields(NovaRequest $request): array
  {
    return [
      ID::make()->sortable(),

      Text::make(AttributeName::TITLE)
        ->sortable()
        ->rules('required', 'max:255'),

      Text::make(AttributeName::SLUG)
        ->sortable()
        ->rules('required', 'max:255'),

      Code::make(AttributeName::FILTER)
        ->json()
        ->showOnPreview()
        ->canSee(fn(NovaRequest $request) => $request->user()->can(RoleConstants::ROOT)),

      DateTime::make(AttributeName::CREATED_AT)
        ->hideFromIndex()
        ->readonly(),

      DateTime::make(AttributeName::UPDATED_AT)
        ->hideFromIndex()
        ->readonly()
    ];
  }
}
