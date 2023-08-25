<?php

namespace App\Nova\Content;

use App\Constants\Attributes\AttributeName;
use App\Constants\MediaConstants;
use App\Nova\Resource;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Bank extends Resource
{
  public static string $model = \App\Models\Bank::class;
  public static $title = AttributeName::NAME;
  public static $search = [
    AttributeName::ID, AttributeName::NAME, AttributeName::SLUG
  ];
  public static $perPageOptions = [50, 100, 150];
  public static $with = ['media'];
  public static ?string $defaultSort = AttributeName::NAME;

  public function fields(NovaRequest $request): array
  {
    return [
      ID::make()->sortable(),
      Text::make(AttributeName::TITLE)
        ->sortable()
        ->rules('max:180')
        ->hideFromIndex(),

      Text::make(AttributeName::NAME)
        ->sortable()
        ->rules('required', 'max:180')
        ->showOnPreview(),

      Images::make('Image', MediaConstants::MEDIA_BANKS)
        ->showOnPreview()
        ->rules('required'),

      Images::make('Image Car', MediaConstants::MEDIA_BANKS_CAR)
        ->showOnPreview(),

      Files::make('License File', MediaConstants::MEDIA_BANKS_LICENSE),

      Text::make('License Title', AttributeName::LICENSE_TITLE)->nullable()->showOnIndex(false)
        ->rules('max:50'),

      Slug::make(AttributeName::SLUG)
        ->from(AttributeName::TITLE)
        ->hideFromIndex()
        ->showOnPreview(),

      Number::make(AttributeName::REQUEST)
      ->hideFromIndex()
      ->showOnPreview(),
      Number::make(AttributeName::APPROVAL)
        ->hideFromIndex()
        ->showOnPreview(),

      Text::make(AttributeName::RATE)
        ->hideFromIndex()
        ->showOnPreview(),

      Text::make(AttributeName::RATING)
        ->showOnPreview(),

      Boolean::make(AttributeName::IS_ACTIVE),

      MorphMany::make('siteTexts'),
    ];
  }
}
