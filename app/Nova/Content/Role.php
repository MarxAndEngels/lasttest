<?php

namespace App\Nova\Content;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Resource;

class Role extends Resource
{
  /**
   * The model the resource corresponds to.
   *
   * @var class-string<\App\Models\Admin\Role>
   */
  public static $model = \App\Models\Role::class;

  /**
   * The single value that should be used to represent the resource when being displayed.
   *
   * @var string
   */
  public static $title = 'name';

  /**
   * The columns that should be searched.
   *
   * @var array
   */
  public static $search = [
    'id',
  ];

  /**
   * Get the fields displayed by the resource.
   *
   * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
   * @return array
   */
  public function fields(NovaRequest $request)
  {
    return [
      ID::make()->sortable(),
      Text::make('Name')
        ->sortable()
        ->rules('required', 'max:160'),
      Text::make('Guard_name')
        ->sortable()
        ->readonly()
        ->hide()
        ->hideFromIndex()
        ->rules('required', 'max:20')->default(function ($request) {
          return 'web';
        }),
      Text::make('Дата создания','created_at')->hideWhenCreating()->hideFromIndex()->readonly(function ($request) {
        return $request->isCreateOrAttachRequest();
      }),
      Text::make('Дата обновления','updated_at')->hideWhenCreating()->hideFromIndex()->readonly(function ($request) {
        return $request->isCreateOrAttachRequest();
      }),

    ];
  }

  /**
   * Get the cards available for the request.
   *
   * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
   * @return array
   */
  public function cards(NovaRequest $request)
  {
    return [];
  }

  /**
   * Get the filters available for the resource.
   *
   * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
   * @return array
   */
  public function filters(NovaRequest $request)
  {
    return [];
  }

  /**
   * Get the lenses available for the resource.
   *
   * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
   * @return array
   */
  public function lenses(NovaRequest $request)
  {
    return [];
  }

  /**
   * Get the actions available for the resource.
   *
   * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
   * @return array
   */
  public function actions(NovaRequest $request)
  {
    return [];
  }
}
