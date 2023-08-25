<?php

namespace App\Nova\Filters;

use App\Constants\Attributes\AttributeName;
use App\Models\Dealer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class SiteDealerFilterNova extends Filter
{
  public $name = 'Dealer';
  public function apply(NovaRequest $request, $query, $value): Builder
  {
    if ((int)$value){
      $query->where(AttributeName::DEALER_ID, '=', (int)$value);
    }

    return $query;
  }

  public function options(NovaRequest $request): array
  {
    return Dealer::query()->whereHas('site')->orderBy(AttributeName::TITLE, 'ASC')->get()->mapWithKeys(fn(Dealer $dealer) =>
      [
        $dealer->title => $dealer->id
      ]
    )->all();
  }
}
