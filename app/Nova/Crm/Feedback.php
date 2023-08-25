<?php

namespace App\Nova\Crm;

use App\Constants\Attributes\AttributeName;
use App\Constants\Enums\FeedbackEnum;
use App\Nova\Crm\Offer\FeedbackOffer;
use App\Nova\Filters\FeedbackFilterSiteNova;
use App\Nova\Filters\FeedbackFilterStatusNova;
use App\Nova\Metrics\FeedbackMetricTrendNova;
use App\Nova\Metrics\FeedbackMetricValueNova;
use App\Nova\Resource;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\Status;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Feedback extends Resource
{
  public static string $model = \App\Models\Feedback::class;
  public static $title = AttributeName::TITLE;
  public static $search = [
    AttributeName::EXTERNAL_ID, AttributeName::CLIENT_PHONE, AttributeName::CLIENT_IP
  ];
  public static $with = ['site'];

  public function cards(NovaRequest $request): array
  {
    return [
      new FeedbackMetricValueNova(),
      new FeedbackMetricTrendNova()
    ];
  }

  public function filters(NovaRequest $request): array
  {
    return [
      new FeedbackFilterStatusNova(),
      new FeedbackFilterSiteNova()
    ];
  }
  public function fields(NovaRequest $request): array
  {
    return [
      ID::make()->sortable(),

      Text::make(AttributeName::EXTERNAL_ID)
        ->sortable(),

      Status::make('Status', AttributeName::STATUS_ENUM)
        ->loadingWhen([FeedbackEnum::NEW])
        ->failedWhen([FeedbackEnum::ERROR])
        ->onlyOnIndex(),

      Select::make('Status', AttributeName::STATUS_ENUM)
        ->onlyOnForms()
        ->options(FeedbackEnum::STATUS_ENUM),


      Text::make('Type', AttributeName::TYPE_ENUM)
        ->readonly()
        ->rules('required'),

      BelongsTo::make('site', 'site', Site::class),

      Text::make(AttributeName::CLIENT_IP)
        ->readonly()
        ->hideFromIndex()
        ->rules('required'),
      Text::make(AttributeName::CLIENT_USER_AGENT)
        ->readonly()
        ->hideFromIndex()
        ->rules('required'),

      Text::make(AttributeName::CLIENT_NAME)
        ->readonly()
        ->rules('required'),

      Text::make(AttributeName::CLIENT_PHONE)
        ->readonly()
        ->rules('required'),

      Text::make(AttributeName::CLIENT_AGE)
        ->readonly()
        ->hideFromIndex()
        ->rules('required'),

      Text::make(AttributeName::CLIENT_REGION)
        ->readonly()
        ->hideFromIndex(),

      Text::make(AttributeName::CREDIT_INITIAL_FEE)
        ->readonly()
        ->hideFromIndex(),


      Text::make(AttributeName::CREDIT_PERIOD)
        ->readonly()
        ->hideFromIndex(),



      Stack::make('CLIENT CAR', [
        Text::make(AttributeName::CLIENT_VEHICLE_MARK)
          ->readonly(),
        Text::make(AttributeName::CLIENT_VEHICLE_MODEL)
          ->readonly(),
        Text::make(AttributeName::CLIENT_VEHICLE_RUN)
          ->readonly(),
        Text::make(AttributeName::CLIENT_VEHICLE_YEAR),
        Text::make(AttributeName::CLIENT_VEHICLE_BODY_TYPE)
          ->readonly(),
        Text::make(AttributeName::CLIENT_VEHICLE_PRICE)
          ->readonly(),
        Text::make(AttributeName::CLIENT_VEHICLE_OWNERS)
          ->readonly(),
        Text::make(AttributeName::CLIENT_VEHICLE_GEARBOX)
          ->readonly(),
        Text::make(AttributeName::CLIENT_VEHICLE_ENGINE)
          ->readonly(),
      ])->hideFromIndex(),

      Stack::make('UTM', [
        Text::make(AttributeName::UTM_SOURCE)
          ->readonly(),
        Text::make(AttributeName::UTM_MEDIUM)
          ->readonly(),
        Text::make(AttributeName::UTM_CAMPAIGN)
          ->readonly(),
        Text::make(AttributeName::UTM_CONTENT)
          ->readonly(),
        Text::make(AttributeName::UTM_TERM)
          ->readonly(),
        ]),

      Text::make(AttributeName::OFFER_TITLE)
        ->readonly()
        ->hideFromIndex(),


      Textarea::make(AttributeName::COMMENT)
        ->readonly()
        ->hideFromIndex(),


      Text::make(AttributeName::CLIENT_AGE)
        ->readonly()
        ->hideFromIndex(),


      DateTime::make(AttributeName::CREATED_AT)
        ->readonly(),

      DateTime::make(AttributeName::UPDATED_AT)
        ->hideFromIndex()
        ->readonly(),

      HasOne::make('feedbackOffer', 'feedbackOffer', FeedbackOffer::class)
    ];
  }
}
