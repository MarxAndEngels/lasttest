<?php

namespace App\Nova\Site;

use App\Constants\Attributes\AttributeName;
use App\Nova\Crm\Site;
use App\Nova\Resource;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Query\Search\SearchableRelation;

class SiteSetting extends Resource
{
  public static string $model = \App\Models\SiteSetting::class;
  public static function searchableColumns(): array
  {
    return ['id', new SearchableRelation('site', 'title')];
  }
  public function fields(NovaRequest $request): array
  {
    return [
      ID::make()->sortable(),
      BelongsTo::make('site', 'site', Site::class)->required()->sortable(),

      KeyValue::make(AttributeName::SETTINGS)
        ->rules('json'),

    ];
  }
}
