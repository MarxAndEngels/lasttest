<?php

namespace App\Nova\Crm;

use App\Constants\Attributes\AttributeName;
use App\Constants\Enums\OfferEnum;
use App\Constants\Permission\RoleConstants;
use App\Constants\Permission\SitePermission;
use App\Nova\Actions\OfferReportAction;
use App\Nova\Crm\Offer\Offer;
use App\Nova\Filters\SiteDealerFilterNova;
use App\Nova\Filters\SiteFilterStatusNova;
use App\Nova\Resource;
use App\Nova\Site\Region;
use App\Nova\Site\SiteSetting;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Query\Search\SearchableRelation;

class Site extends Resource
{
  public static string $model = \App\Models\Site::class;
  public static $title = AttributeName::TITLE;
  public bool $isRoot = false;
  public static $search = [
    AttributeName::ID, AttributeName::TITLE, AttributeName::SLUG, AttributeName::URL, 'dealer.title'
  ];

  public static function searchableColumns(): array
  {
    return [ AttributeName::ID, AttributeName::TITLE, AttributeName::SLUG, AttributeName::URL, new SearchableRelation('dealer', AttributeName::TITLE)];
  }
  public function actions(NovaRequest $request) : array
  {
    return [
      (new OfferReportAction($request->user()))->onlyOnDetail()->canRun(
        fn(NovaRequest $request) => $request->user()->can(SitePermission::OFFER_REPORT_ACTION)
      ),
    ];
  }

  public function filters(NovaRequest $request): array
  {
    return [
      new SiteFilterStatusNova(),
      new SiteDealerFilterNova()
    ];
  }

  public function fields(NovaRequest $request): array
  {
    $this->isRoot = $request->user()->can(RoleConstants::ROOT);
    $typeEnums = implode(',', OfferEnum::TYPE_ENUM);
    $categories = implode(',', OfferEnum::CATEGORY_ENUM);
    return [
      ID::make()->sortable(),

      Text::make(AttributeName::EXTERNAL_ID)
        ->sortable()
        ->rules('required', 'integer')
        ->showOnPreview()
        ->canSee(fn() => $this->isRoot),

      Text::make(AttributeName::TITLE)
        ->sortable()
        ->rules('required', 'max:255')
        ->showOnPreview(),

      BelongsTo::make('Parent Site', 'parentSite', Site::class)->nullable(),

      URL::make(AttributeName::URL)
        ->rules('required', 'url')
        ->showOnPreview(),


      BelongsTo::make('dealer', 'dealer', Dealer::class)->nullable(),

      Text::make(AttributeName::LEGAL_NAME)
        ->hideFromIndex()
        ->canSee(fn() => $this->isRoot),
      Text::make(AttributeName::CITY)
        ->hideFromIndex()
        ->canSee(fn() => $this->isRoot),

      Text::make(AttributeName::EMAIL_SERVICE)
        ->hideFromIndex()
        ->canSee(fn() => $this->isRoot),

      Slug::make(AttributeName::SLUG)
        ->from(AttributeName::TITLE)
        ->hideFromIndex(fn() => $this->isRoot)
        ->showOnPreview(),

      Text::make(AttributeName::CATEGORY_URL)
        ->hideFromIndex()
        ->sortable()
        ->rules('required', 'max:255')
        ->showOnPreview()
        ->canSee(fn() => $this->isRoot),

      Code::make(AttributeName::ROUTE_PAGES)
        ->json()
        ->showOnPreview(),

      Boolean::make(AttributeName::BANK_PAGES)
        ->sortable()
        ->hideFromIndex()
        ->canSee(fn() => $this->isRoot),


      Boolean::make(AttributeName::DEALER_PAGES)
        ->sortable()
        ->hideFromIndex()
        ->canSee(fn() => $this->isRoot),

      Code::make(AttributeName::FILTER)
        ->help("<p>Available keys: typeEnum | minYear | category | minPrice</p>
                    <p>typeEnum: {$typeEnums}</p>
                    <p>category: {$categories}</p>"
        )
        ->json()
        ->showOnPreview()
        ->canSee(fn() => $this->isRoot),

      KeyValue::make(AttributeName::CATEGORY_ASSOCIATION)
        ->keyLabel('Category')
        ->valueLabel('Association')
        ->rules('json')
        ->actionText('Add Item')
        ->canSee(fn() => $this->isRoot),

      DateTime::make(AttributeName::API_DATE_LAST)
        ->readonly()
        ->canSee(fn() => $this->isRoot),

      DateTime::make(AttributeName::API_DATE_FROM)
        ->hideFromIndex()
        ->showOnPreview()
        ->canSee(fn() => $this->isRoot),

      Boolean::make(AttributeName::GENERATION_URL)
        ->sortable()
        ->hideFromIndex()
        ->canSee(fn() => $this->isRoot),

      Boolean::make(AttributeName::POST_LINK_CRM)
        ->sortable()
        ->hideFromIndex()
        ->canSee(fn() => $this->isRoot),

      Boolean::make(AttributeName::POST_FEEDBACK_PLEX_CRM)
        ->sortable()
        ->hideFromIndex()
        ->default(true)
        ->canSee(fn() => $this->isRoot),

      Boolean::make(AttributeName::POST_FEEDBACK_EMAIL)
        ->sortable()
        ->hideFromIndex()
        ->default(false)
        ->canSee(fn() => $this->isRoot),

      Text::make(AttributeName::FEEDBACK_EMAIL)
        ->hideFromIndex()
        ->canSee(fn() => $this->isRoot),
//        ->dependsOn(AttributeName::POST_FEEDBACK_EMAIL, function (Text $field, NovaRequest $request, FormData $formData) {
//          if ($formData->post_feedback_email) {
//            $field->show()->rules('required');
//          }else{
//            $field->hide();
//          }
//      }),

      Boolean::make(AttributeName::GET_COMMUNICATIONS)
        ->sortable()
        ->hideFromIndex()
        ->default(false)
        ->canSee(fn() => $this->isRoot),

      Boolean::make(AttributeName::IS_DISABLED)
        ->canSee(fn() => $this->isRoot)
        ->sortable(),

      BelongsToMany::make('Offers', 'offerSites', Offer::class)
        ->fields(fn() => [
          Text::make(AttributeName::PRICE),
          Text::make(AttributeName::PRICE_OLD),
          Text::make(AttributeName::IS_ACTIVE),
        ])
        ->canSee(fn() => $this->isRoot),

      HasMany::make('Dealers', 'dealers', Dealer::class)->canSee(fn() => $this->isRoot),
      HasMany::make('Settings', 'settings', SiteSetting::class),

      BelongsToMany::make('Regions', 'regions', Region::class)->fields(fn(NovaRequest $request) => [
        Text::make(AttributeName::ORDER_COLUMN),
      ]),
    ];
  }
}
