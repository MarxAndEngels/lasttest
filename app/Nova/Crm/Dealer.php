<?php

namespace App\Nova\Crm;

use App\Constants\Attributes\AttributeName;
use App\Constants\MediaConstants;
use App\Constants\Permission\RoleConstants;
use App\Nova\Resource;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\URL;

class Dealer extends Resource
{
  public static string $model = \App\Models\Dealer::class;
  public static $title = AttributeName::TITLE;
  public static $search = [
    AttributeName::ID, AttributeName::TITLE
  ];

  public function fields(NovaRequest $request): array
  {
    return [
      ID::make()->sortable(),

      Text::make(AttributeName::EXTERNAL_ID)
        ->sortable()
        ->rules('required', 'integer')
        ->showOnPreview()
        ->canSee(fn(NovaRequest $request) => $request->user()->can(RoleConstants::ROOT)),

      Text::make(AttributeName::TITLE)
        ->sortable()
        ->rules('required', 'max:255')
        ->showOnPreview(),

      Slug::make(AttributeName::SLUG)
        ->from(AttributeName::TITLE)
        ->hideFromIndex()
        ->showOnPreview(),

      Images::make(AttributeName::IMAGE_LOGO, MediaConstants::MEDIA_DEALER_LOGO)
      ->croppable(false),

      Images::make(AttributeName::IMAGES, MediaConstants::MEDIA_DEALERS)
        ->conversionOnPreview(MediaConstants::CONVERSION_SMALL)
        ->conversionOnDetailView(MediaConstants::CONVERSION_SMALL)
        ->conversionOnIndexView(MediaConstants::CONVERSION_SMALL)
        ->conversionOnForm(MediaConstants::CONVERSION_SMALL)
        ->fullSize()
        ->singleImageRules('dimensions:min_width=100')
        ->croppable(false)
        ->showStatistics(),

      Text::make(AttributeName::CITY)
        ->hideFromIndex()
        ->showOnPreview(),

      Text::make(AttributeName::ADDRESS)
        ->hideFromIndex()
        ->showOnPreview(),

      Text::make(AttributeName::METRO)
        ->hideFromIndex()
        ->showOnPreview(),

      Text::make(AttributeName::COORDINATES)
        ->hideFromIndex()
        ->showOnPreview(),

      URL::make(AttributeName::SITE)
        ->hideFromIndex()
        ->showOnPreview(),

      Text::make(AttributeName::PHONE)
        ->hideFromIndex()
        ->showOnPreview(),

      Text::make(AttributeName::SCHEDULE)
        ->hideFromIndex()
        ->showOnPreview(),


      Text::make(AttributeName::YOUTUBE_PLAYLIST_REVIEW)
        ->hideFromIndex(),

      Text::make(AttributeName::RATING)
        ->hideFromIndex()
        ->showOnPreview(),

      Textarea::make(AttributeName::SHORT_DESCRIPTION)
        ->hideFromIndex()
        ->showOnPreview(),

      Textarea::make(AttributeName::DESCRIPTION)
        ->hideFromIndex()
        ->showOnPreview(),

      DateTime::make(AttributeName::CREATED_AT)
        ->hideFromIndex()
        ->readonly(),

      DateTime::make(AttributeName::UPDATED_AT)
        ->hideFromIndex()
        ->readonly(),

    ];
  }
}
