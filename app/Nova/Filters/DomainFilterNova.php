<?php

namespace App\Nova\Filters;

use App\Constants\Attributes\AttributeName;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Filters\BooleanFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class DomainFilterNova extends BooleanFilter
{
  public $name = 'Status';
  public function apply(NovaRequest $request, $query, $value): Builder
  {

    if ($value[AttributeName::WARNING]){
      $day30 = Carbon::now()->addDays(30);
      $query
        ->where(AttributeName::DATE_EXPIRE, '>=', Carbon::now()->toDateString())
        ->where(AttributeName::DATE_EXPIRE, '<=', $day30->toDateString());
    }
    if ($value[AttributeName::INFO]){
      $query
        ->where(AttributeName::AVAILABLE, '=', true);
    }
    return $query;
  }

  public function options(NovaRequest $request): array
  {
    return [
      'Заканчивается (<=30)' => AttributeName::WARNING,
      'Свободные' => AttributeName::INFO,
    ];
  }
}
