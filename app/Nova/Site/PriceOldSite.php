<?php

namespace App\Nova\Site;

use App\Constants\Attributes\AttributeName;
use App\Nova\Crm\Site;
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
use Laravel\Nova\Query\Search\SearchableRelation;

class PriceOldSite extends Resource
{
  public static string $model = \App\Models\PriceOldSite::class;

  public static function searchableColumns(): array
  {
    return [AttributeName::ID, new SearchableRelation('site', 'title')];
  }

  public static function label(): string
  {
    return 'PriceOlds';
  }
  public function fields(NovaRequest $request): array
  {
    return [
      ID::make()->sortable(),

      BelongsTo::make('site', 'site', Site::class)->sortable(),

      Code::make(AttributeName::LOGIC)
        ->json()
        ->showOnPreview(),

      DateTime::make(AttributeName::CREATED_AT)
        ->readonly(),

      DateTime::make(AttributeName::UPDATED_AT)
        ->readonly()
    ];
  }
}
