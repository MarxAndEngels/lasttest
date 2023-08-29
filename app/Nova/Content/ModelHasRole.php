<?php

namespace App\Nova\Content;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Resource;

class ModelHasRole extends Resource
{
  /**
   * The model the resource corresponds to.
   *
   * @var class-string<\App\Models\Admin\ModelHasRole>
   */
  public static $model = \App\Models\ModelHasRole::class;

  /**
   * The single value that should be used to represent the resource when being displayed.
   *
   * @var string
   */
  public static $title = 'role_id';

  /**
   * The columns that should be searched.
   *
   * @var array
   */
  public static $search = [
    'role_id',
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
//          ID::make()->sortable(),
      BelongsTo::make('user'),
      BelongsTo::make('role'),
      Text::make('model_type')
        ->readonly()
        ->hide()
        ->hideFromIndex()
        ->rules('required', 'max:20')->default(function ($request) {
          return 'App\Models\User';
        }),
//          BelongsTo::make('role_id')
//          BelongsTo::make('User'),
//          BelongsTo::make('Role'),
//
//          Text::make('Guard_name')
//            ->sortable()
//            ->readonly()
//            ->hideWhenCreating()
//            ->rules('required', 'max:20')->default(function ($request) {
//              return 'web';
//            }),
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
