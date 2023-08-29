<?php

namespace App\Nova\Content;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Resource;

class Dealer extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Content\Dealer>
     */
    public static $model = \App\Models\Dealer::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id','title','slug'
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
          Text::make('Title')
            ->sortable()
            ->rules('required', 'max:160'),
          Text::make('Slug')
            ->sortable()
            ->rules('required', 'max:180')
            ->creationRules('unique:dealers,slug')
            ->updateRules('unique:dealers,slug,{{resourceId}}')
            ->showOnPreview(),
          Text::make('City')
            ->sortable()
            ->rules('required', 'max:80')
            ->showOnPreview(),
          BelongsTo::make('User'),
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
