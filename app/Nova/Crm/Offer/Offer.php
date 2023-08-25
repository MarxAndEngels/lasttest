<?php

namespace App\Nova\Crm\Offer;

use App\Constants\Attributes\AttributeName;
use App\Constants\Permission\RoleConstants;
use App\Nova\Crm\Site;
use App\Nova\Resource;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Offer extends Resource
{
  public static string $model = \App\Models\Offer::class;
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
        ->readonly()
        ->canSee(fn() => $this->isRoot),

      Text::make(AttributeName::EXTERNAL_UNIQUE_ID)
        ->sortable()
        ->readonly()
        ->rules('required', 'integer')
        ->canSee(fn() => $this->isRoot),

      Image::make('image', 'images')
        ->resolveUsing(function ($images) {;
          return $images[0]['thumb'];
        })
        ->preview(function ($value) {
          return $value;
        })
        ->thumbnail(function ($value) {
          return $value;
        })
        ->exceptOnForms()
        ->disableDownload(),

      Text::make(AttributeName::NAME)
        ->sortable()
        ->readonly()
        ->rules('required', 'max:255'),

      Text::make(AttributeName::CATEGORY_ENUM)
        ->sortable()
        ->readonly()
        ->rules('required', 'max:255')
        ->canSee(fn() => $this->isRoot),

      Text::make(AttributeName::SECTION_ENUM)
        ->sortable()
        ->readonly()
        ->rules('required', 'max:255')
        ->canSee(fn() => $this->isRoot),

      Text::make(AttributeName::TYPE_ENUM)
        ->sortable()
        ->readonly()
        ->rules('required', 'max:255')
        ->canSee(fn() => $this->isRoot),

      Text::make(AttributeName::VIN)
        ->sortable()
        ->readonly()
        ->rules('required', 'max:255'),

      DateTime::make(AttributeName::CREATED_AT)
        ->hideFromIndex()
        ->readonly(),

      DateTime::make(AttributeName::UPDATED_AT)
        ->hideFromIndex()
        ->readonly(),

      BelongsToMany::make('sites', 'offerSites', Site::class)->fields(fn()=> [
        Text::make(AttributeName::PRICE),
        Text::make(AttributeName::PRICE_OLD),
        Text::make('Active offer', AttributeName::IS_ACTIVE),
      ]),
    ];
  }
}
