<?php

namespace App\Nova\Content;

use App\Nova\Resource;
use Illuminate\Validation\Rules;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class User extends Resource
{
  public static string $model = \App\Models\User::class;

  public static $title = 'name';

  public static $search = [
    'id', 'name', 'email',
  ];
  public function fields(NovaRequest $request): array
  {
    return [
      ID::make()->sortable(),

      Gravatar::make()->maxWidth(50),

      Text::make('Name')
        ->sortable()
        ->rules('required', 'max:255')
        ->creationRules('unique:users,name')
        ->updateRules('unique:users,name,{{resourceId}}'),

      Text::make('Email')
        ->sortable()
        ->rules('required', 'email', 'max:254')
        ->creationRules('unique:users,email')
        ->updateRules('unique:users,email,{{resourceId}}'),

      Password::make('Password')
        ->onlyOnForms()
        ->creationRules('required', Rules\Password::defaults())
        ->updateRules('nullable', Rules\Password::defaults()),
      HasMany::make('Dealer'),
     MorphToMany::make('Roles', 'roles', \Itsmejoshua\Novaspatiepermissions\Role::class),
     MorphToMany::make('Permissions', 'permissions', \Itsmejoshua\Novaspatiepermissions\Permission::class),
    ];
  }

 public function cards(NovaRequest $request): array
 {
   return [];
 }

 public function filters(NovaRequest $request): array
 {
   return [];
 }

 public function lenses(NovaRequest $request): array
 {
   return [];
 }

 public function actions(NovaRequest $request): array
 {
   return [];
 }
}
