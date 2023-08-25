<?php

namespace App\Models;

use App\Complex\Eloquent\ReferenceModel;

class Set extends ReferenceModel
{
  public $casts = [
    'filter' => 'array'
  ];
}
