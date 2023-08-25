<?php

namespace App\Nova\Filters;

use App\Constants\Attributes\AttributeName;
use App\Models\Site;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class FeedbackFilterSiteNova extends Filter
{
  public $component = 'select-filter';
  public $name = AttributeName::SITE;

  public function apply(NovaRequest $request, $query, $value)
  {
    return $query->where(AttributeName::SITE_ID, '=', $value);
  }

  public function options(NovaRequest $request): array
  {
    $siteQuery = Site::query();
    return $siteQuery->select([AttributeName::ID, AttributeName::TITLE])
                      ->orderBy(AttributeName::TITLE, 'asc')
                      ->get()
                      ->mapWithKeys(fn($site) => [$site->title => $site->id])
                      ->toArray();
  }
}
