<?php

namespace App\Http\Controllers;

use App\Models\ModelHasRole;
use Illuminate\Http\Request;

class DealerController extends Controller
{
    public function __invoke()
    {
      $table = ModelHasRole::first()->user;
      dd($table);
    }
}
