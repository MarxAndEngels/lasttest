<?php

namespace App\Nova\Metrics;

use App\Models\Feedback;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Metrics\TrendResult;

class FeedbackMetricTrendNova extends Trend
{
  public $name = 'Feedback trend';
  public function calculate(NovaRequest $request): TrendResult
  {
    return $this->countByDays($request, Feedback::class);
  }

  public function ranges(): array
  {
    return [
      30 => __('30 Days'),
      60 => __('60 Days'),
      90 => __('90 Days'),
    ];
  }

  /**
   * Determine the amount of time the results of the metric should be cached.
   *
   * @return \DateTimeInterface|\DateInterval|float|int|null
   */
  public function cacheFor()
  {
    // return now()->addMinutes(5);
  }

  public function uriKey(): string
  {
    return 'feedback-metric-trend-nova';
  }
}
