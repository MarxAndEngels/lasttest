<?php

namespace App\Nova\Site;

use App\Constants\Attributes\AttributeName;
use App\Constants\RouteNameConstants;
use App\Nova\Crm\Site;
use App\Nova\Resource;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Query\Search\SearchableRelation;
use App\Models\FeedFilter as FeedFilterModel;

class FeedFilter extends Resource
{
  public static $title = AttributeName::NAME;
  public static string $model = FeedFilterModel::class;
  public static function searchableColumns(): array
  {
    return [AttributeName::NAME, new SearchableRelation('site', 'title')];
  }
  public function fields(NovaRequest $request): array
  {
    return [
      ID::make()->sortable(),
      BelongsTo::make('site', 'site', Site::class)->required()->sortable(),

      Text::make(AttributeName::NAME)->rules('max:100')->required(),

      Code::make(AttributeName::FILTER)->json(),

      Boolean::make(AttributeName::GENERATE_FILE)->default(false)->hideFromIndex(),

      Boolean::make(AttributeName::FEED_YANDEX_XML)
        ->hideFromIndex()
        ->hide()
        ->default(false)
        ->dependsOn(AttributeName::GENERATE_FILE,  function (Boolean $field, NovaRequest $request, FormData $formData) {
          if ($formData->boolean(AttributeName::GENERATE_FILE)){
            $field->show();
          }
        }),

      Boolean::make(AttributeName::FEED_VK_XML)
        ->hideFromIndex()
        ->hide()
        ->default(false)
        ->dependsOn(AttributeName::GENERATE_FILE,  function (Boolean $field, NovaRequest $request, FormData $formData) {
          if ($formData->boolean(AttributeName::GENERATE_FILE)){
            $field->show();
          }
        }),

      Boolean::make(AttributeName::FEED_YANDEX_YML)
        ->hideFromIndex()
        ->hide()
        ->default(false)
        ->dependsOn(AttributeName::GENERATE_FILE,  function (Boolean $field, NovaRequest $request, FormData $formData) {
          if ($formData->boolean(AttributeName::GENERATE_FILE)){
            $field->show();
          }
        }),

      Text::make('Link '. RouteNameConstants::YANDEX_XML_FEED_FILTER, fn (FeedFilterModel $formData) =>
        $formData->generate_file ?
          $formData->feed_yandex_xml ? route(RouteNameConstants::XML_FEED_FILTER_FILE, ['yandex', 'xml', $this->site->slug, $this->name], false) : ''
          :
          route(RouteNameConstants::YANDEX_XML_FEED_FILTER, [$this->site->slug, $this->name], false)
      )->hideFromIndex()->readonly(),

      Text::make('Link '. RouteNameConstants::GOOGLE_XML_FEED_FILTER, fn (FeedFilterModel $formData) =>
      $formData->generate_file ?
        ''
        :
        route(RouteNameConstants::GOOGLE_XML_FEED_FILTER, [$this->site->slug, $this->name], false)
      )->hideFromIndex()->readonly(),

      Text::make('Link '. RouteNameConstants::YANDEX_YML_FEED_FILTER, fn (FeedFilterModel $formData) =>
      $formData->generate_file ?
        $formData->feed_yandex_yml ? route(RouteNameConstants::XML_FEED_FILTER_FILE, ['yandex', 'yml', $this->site->slug, $this->name], false): ''
        :
        route(RouteNameConstants::YANDEX_YML_FEED_FILTER, [$this->site->slug, $this->name], false)
      )->hideFromIndex()->readonly(),

      Text::make('Link '. RouteNameConstants::YANDEX_YML_SHORT_FEED_FILTER, fn (FeedFilterModel $formData) =>
      $formData->generate_file ?
        ''
        :
        route(RouteNameConstants::YANDEX_YML_SHORT_FEED_FILTER, [$this->site->slug, $this->name], false)
      )->hideFromIndex()->readonly(),


      Text::make('Link '. RouteNameConstants::VK_XML_FEED_FILTER, fn (FeedFilterModel $formData) =>
      $formData->generate_file ?
        $formData->feed_vk_xml ? route(RouteNameConstants::XML_FEED_FILTER_FILE, ['vk', 'xml', $this->site->slug, $this->name], false) : ''
        :
        route(RouteNameConstants::VK_XML_FEED_FILTER, [$this->site->slug, $this->name], false)
      )->hideFromIndex()->readonly(),

      DateTime::make(AttributeName::DOWNLOAD_AT)
        ->readonly(),
      DateTime::make(AttributeName::GENERATE_FILE_AT)
        ->readonly(),
    ];
  }
}
