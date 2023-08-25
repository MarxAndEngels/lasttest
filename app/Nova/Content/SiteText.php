<?php

namespace App\Nova\Content;

use App\Constants\Attributes\AttributeName;
use App\Nova\Crm\Site;
use App\Nova\Resource;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;

class SiteText extends Resource
{
  public static string $model = \App\Models\SiteText::class;

  public function fields(NovaRequest $request): array
  {
    return [
      ID::make()->sortable(),
      BelongsTo::make('site', 'site', Site::class),
      Code::make(AttributeName::BODY)
        ->hideFromIndex(),
    ];
  }
}
