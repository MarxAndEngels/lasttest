<?php

namespace App\Nova\Content;

use App\Constants\Attributes\AttributeName;
use App\Nova\Resource;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class ArticleCategory extends Resource
{
  public static string $model = \App\Models\ArticleCategory::class;
  public static $title = AttributeName::PAGE_TITLE;

  public static $search = [
    AttributeName::ID, AttributeName::PAGE_TITLE
  ];

  public static function label(): string
  {
    return 'Articles';
  }

  public function fields(NovaRequest $request): array
  {
    return [
      ID::make()->sortable(),

      Text::make(AttributeName::PAGE_TITLE)
        ->sortable()
        ->rules('max:255'),

      Text::make('SEO Title', AttributeName::LONG_TITLE)
        ->sortable()
        ->rules('max:255')
        ->hideFromIndex(),

      Textarea::make('SEO Description', AttributeName::DESCRIPTION)
        ->rules('max:255')
        ->hideFromIndex(),

      Slug::make(AttributeName::SLUG)
        ->from(AttributeName::PAGE_TITLE)
        ->hideFromIndex(),

      Text::make(AttributeName::URL)->readonly(function ($request) {
        return $request->isCreateOrAttachRequest();
      })->hideFromIndex(),

      Boolean::make(AttributeName::URL_OVERRIDE),
      Boolean::make(AttributeName::IS_ACTIVE),

      HasMany::make('Articles', 'articles', Article::class),

    ];
  }
}
