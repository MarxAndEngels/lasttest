<?php

namespace App\Nova\Crm\Offer;

use App\Constants\Attributes\AttributeName;
use App\Constants\Permission\RoleConstants;
use App\Nova\Resource;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class FeedbackOffer extends Resource
{
  public static string $model = \App\Models\FeedbackOffer::class;
  public static $title = AttributeName::NAME;
  public bool $isRoot = false;
  public static $search = [
    AttributeName::EXTERNAL_ID
  ];

  public function fields(NovaRequest $request): array
  {
    $this->isRoot = $request->user()->can(RoleConstants::ROOT);
    return [
      ID::make()->sortable(),

      Text::make(AttributeName::EXTERNAL_ID)
        ->sortable()
        ->rules('required', 'integer')
        ->readonly(),

      Text::make(AttributeName::EXTERNAL_UNIQUE_ID)
        ->sortable()
        ->readonly()
        ->rules('required', 'integer'),


      Text::make(AttributeName::OFFER_TITLE)
        ->sortable()
        ->readonly()
        ->rules('required', 'max:255'),
    ];
  }
}
