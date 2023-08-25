<?php

namespace App\Nova\Content;

use App\Constants\Attributes\AttributeName;
use App\Constants\Permission\RoleConstants;
use App\Nova\Crm\Site;
use App\Nova\Resource;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class TelegramChannel extends Resource
{

  public static string $model = \App\Models\TelegramChannelSite::class;
  public bool $isRoot = false;

  public function fields(NovaRequest $request): array
  {
    $this->isRoot = $request->user()->can(RoleConstants::ROOT);
    return [
      ID::make()->sortable(),

      BelongsTo::make('site', 'site', Site::class),

      Text::make(AttributeName::TG_API_KEY)
        ->rules('required')
        ->canSee(fn() => $this->isRoot)
        ->hideFromIndex(),

      Text::make(AttributeName::TG_CHAT_ID)
        ->rules('required')
        ->canSee(fn() => $this->isRoot)
        ->hideFromIndex(),

      Code::make(AttributeName::FILTER)->json(),

      Code::make(AttributeName::BODY)
        ->language('html')
        ->rules('required')
        ->help('
        <p>markTitle - Марка</p>
        <p>folderTitle - Модель</p>
        <p>generationName - Поколение</p>
        <p>modificationName - Модификация</p>
        <p>bodyTypeTitle - Тип кузова</p>
        <p>driveTypeTitle - Тип привода</p>
        <p>ownersNumber - Кол-во владельцев</p>
        <p>year - Год</p>
        <p>run - Пробег</p>
        <p>price - Цена</p>
        <p>url - Ссылка</p>
        ')
        ->hideFromIndex(),

      DateTime::make(AttributeName::SEND_AT)
        ->readonly(),

      Boolean::make(AttributeName::IS_ACTIVE),
    ];
  }
}
