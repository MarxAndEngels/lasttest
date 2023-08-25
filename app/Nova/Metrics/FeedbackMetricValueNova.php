<?php

namespace App\Nova\Metrics;

use App\Models\Feedback;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use \Laravel\Nova\Metrics\ValueResult;

class FeedbackMetricValueNova extends Value
{
  public $name = 'Feedback count';
  public function calculate(NovaRequest $request) : ValueResult
  {
    return $this->count($request, Feedback::class);
  }

  /**
   * Get the ranges available for the metric.
   *
   * @return array
   */
  public function ranges() :array
  {
    return [
      'TODAY' => __('Today'),
      30 => __('30 Days'),
      60 => __('60 Days'),
      365 => __('365 Days'),
      'MTD' => __('Month To Date'),
      'QTD' => __('Quarter To Date'),
      'YTD' => __('Year To Date'),
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

  public function uriKey():string
  {
    return 'feedback-metric-value-nova';
  }
}
