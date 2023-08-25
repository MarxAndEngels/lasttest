<?php

declare(strict_types=1);

namespace App\Complex\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentModel;

abstract class ReferenceModel extends EloquentModel
{
  public $timestamps = false;
  protected $guarded = [];
}
