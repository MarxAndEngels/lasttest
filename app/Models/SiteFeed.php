<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteFeed extends Model
{
    use HasFactory;

  protected $table = 'site_feed';
  protected $guarded = false;
  public $timestamps = false;
}
