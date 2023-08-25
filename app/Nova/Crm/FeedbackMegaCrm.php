<?php

namespace App\Nova\Crm;

use App\Constants\Attributes\AttributeName;
use App\Nova\Resource;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Query\Search\SearchableRelation;

class FeedbackMegaCrm extends Resource
{
  public static $title = AttributeName::NAME;
  public static string $model = \App\Models\FeedbackMegaCrm::class;
  public static function searchableColumns(): array
  {
    return [AttributeName::NAME, new SearchableRelation('site', 'title')];
  }
  public function fields(NovaRequest $request): array
  {
    return [
      ID::make()->sortable(),
      BelongsTo::make('site', 'site', Site::class)->required()->sortable(),

      Text::make(AttributeName::TOKEN)->rules('max:100')->required(),

      DateTime::make(AttributeName::DOWNLOAD_AT)
        ->readonly(),
      DateTime::make(AttributeName::LAST_REQUEST_AT)
        ->readonly(),
    ];
  }
}
