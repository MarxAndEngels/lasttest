<?php

namespace App\Nova\Filters;

use App\Constants\Attributes\AttributeName;
use App\Constants\Enums\FeedbackEnum;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class FeedbackFilterStatusNova extends Filter
{

  public $component = 'select-filter';
  public $name = AttributeName::STATUS_ENUM;

  public function apply(NovaRequest $request, $query, mixed $value): Builder
  {
    return $query->where(AttributeName::STATUS_ENUM, '=', $value);
  }

  public function options(NovaRequest $request): array
  {
    return FeedbackEnum::STATUS_ENUM;
  }
}
