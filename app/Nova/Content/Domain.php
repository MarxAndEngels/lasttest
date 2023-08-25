<?php

namespace App\Nova\Content;

use App\Constants\Attributes\AttributeName;
use App\Nova\Filters\DomainFilterNova;
use App\Nova\Resource;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Domain extends Resource
{
  public static string $model = \App\Models\Domain::class;
  public static $title = AttributeName::FQDN;
  public static $search = [
    AttributeName::FQDN
  ];

  public function filters(NovaRequest $request): array
  {
    return [
      new DomainFilterNova(),
    ];
  }
  public static ?string $defaultSort = AttributeName::DATE_EXPIRE;

  public function fields(NovaRequest $request): array
  {
    return [
      ID::make()->sortable(),
      Text::make(AttributeName::FQDN)
        ->sortable()
        ->rules('max:180')
        ->readonly(),
      Date::make(AttributeName::DATE_REGISTER)
        ->hideFromIndex()
        ->readonly(),

      Date::make(AttributeName::DATE_EXPIRE)
        ->sortable()
        ->readonly(),

      Badge::make('Status')
        ->map([
        AttributeName::DANGER => 'danger',
        AttributeName::WARNING => 'warning',
        AttributeName::INFO => 'info',
        AttributeName::SUCCESS => 'success'
      ])->icons([
          AttributeName::DANGER => 'exclamation-circle',
          AttributeName::WARNING => 'exclamation-circle',
          AttributeName::INFO => 'information-circle',
          AttributeName::SUCCESS => 'check-circle'
        ])
        ->labels([
        AttributeName::DANGER => 'Продлить',
        AttributeName::WARNING => 'Заканчивается',
        AttributeName::INFO => 'Свободен',
        AttributeName::SUCCESS => 'ОК'
      ]),
    ];
  }
}
