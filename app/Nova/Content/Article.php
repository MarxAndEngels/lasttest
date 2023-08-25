<?php

namespace App\Nova\Content;

use App\Constants\Attributes\AttributeName;
use App\Constants\MediaConstants;
use App\Nova\Resource;
use Carbon\Carbon;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Murdercode\TinymceEditor\TinymceEditor;
use Media24si\NovaYoutubeField\Youtube;
class Article extends Resource
{
  public static string $model = \App\Models\Article::class;
  public static $title = AttributeName::PAGE_TITLE;
  public static $search = [
    AttributeName::ID, AttributeName::PAGE_TITLE, AttributeName::SLUG
  ];
  public static $perPageOptions = [50, 100, 150];
  public static $with = ['media'];

  public function fields(NovaRequest $request): array
  {
    return [
      ID::make()->sortable(),

      BelongsTo::make('Категория', 'category', ArticleCategory::class)
        ->rules('required'),

      Text::make('Заголовок', AttributeName::PAGE_TITLE)
        ->sortable()
        ->rules('max:255', 'required'),

      Text::make('SEO заголовок', AttributeName::LONG_TITLE)
        ->sortable()
        ->rules('max:255')
        ->hideFromIndex(),

      Textarea::make('Краткое описание', AttributeName::SHORT_DESCRIPTION)
        ->rules('max:255')
        ->hideFromIndex(),

      Textarea::make('SEO описание', AttributeName::DESCRIPTION)
        ->rules('max:255')
        ->hideFromIndex(),


      Slug::make('Псевдоним', AttributeName::SLUG)
        ->from(AttributeName::PAGE_TITLE)
        ->hideFromIndex(),

      Text::make('Ссылка', AttributeName::URL)->readonly(function ($request) {
        return $request->isCreateOrAttachRequest();
      })->hideFromIndex()->readonly(),

      DateTime::make('Дата создания',AttributeName::CREATED_AT)->hideWhenCreating()->hideFromIndex()->readonly(function ($request) {
        return $request->isCreateOrAttachRequest();
      }),
      DateTime::make('Дата обновления',AttributeName::UPDATED_AT)->hideWhenCreating()->hideFromIndex()->readonly(function ($request) {
        return $request->isCreateOrAttachRequest();
      }),
      #Boolean::make('Заморозить URL', AttributeName::URL_OVERRIDE)->default(0)->hideFromIndex(),
      Boolean::make('Опубликован',AttributeName::IS_ACTIVE)->default(1),

      DateTime::make('Дата публикации',AttributeName::PUBLISHED_AT)->default(Carbon::now()),

      new Panel('Видео', $this->videoField()),
      new Panel('Изображение', $this->imageFields()),

      new Panel('Контент', $this->contentFields()),
    ];
  }
    protected function videoField(): array
    {
      return [
        Youtube::make('Видео Youtube', AttributeName::VIDEO_YOUTUBE)->hideFromIndex()
      ];
    }
  protected function imageFields(): array
  {
    return [
      Images::make('Превью изображения', MediaConstants::MEDIA_ARTICLE_PREVIEWS),

      Images::make('Изображение', MediaConstants::MEDIA_ARTICLES)
        ->hideFromIndex(),

      Images::make('Слайдер', MediaConstants::MEDIA_ARTICLE_SLIDE)
        ->conversionOnPreview(MediaConstants::CONVERSION_THUMB)
        ->singleImageRules('dimensions:min_width=480')
        ->hideFromIndex()
    ];
  }
  protected function contentFields(): array
  {
    return [
      TinymceEditor::make('Контент', AttributeName::BODY)
        ->rules(['required'])
        ->hideFromIndex(),
    ];

  }
}
