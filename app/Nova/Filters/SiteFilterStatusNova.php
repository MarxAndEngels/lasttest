<?php

namespace App\Nova\Filters;

use App\Constants\Attributes\AttributeName;
use App\Constants\Permission\RoleConstants;
use Laravel\Nova\Filters\BooleanFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class SiteFilterStatusNova extends BooleanFilter
{
  public $name = 'Active';

  public function apply(NovaRequest $request, $query, $value)
  {
    $query->where(AttributeName::IS_DISABLED, '=', $value[AttributeName::IS_DISABLED]);
    if($value['is_main']){
      $query->whereNull(AttributeName::PARENT_SITE_ID);
    }
     return $query;
  }

  public function options(NovaRequest $request): array
  {
    return [
      AttributeName::IS_DISABLED => AttributeName::IS_DISABLED,
      'is_main' => 'is_main'
    ];
  }
}
