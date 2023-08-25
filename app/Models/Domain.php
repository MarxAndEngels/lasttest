<?php

namespace App\Models;

use App\Constants\Attributes\AttributeName;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
  protected static $unguarded = true;
  protected $appends = ['status'];
  protected $casts = [
    AttributeName::DATE_ADD => 'datetime',
    AttributeName::DATE_REGISTER => 'date',
    AttributeName::DATE_EXPIRE => 'date',
  ];

  public function getStatusAttribute(): string
  {
    $now = Carbon::now();

    if(!$this->date_expire){
      return AttributeName::INFO;
    }
    $days = Carbon::parse($this->date_expire)->diff($now)->days;
    if ($days < 3) {
      return AttributeName::DANGER;
    }
    if ($days <= 30) {
      return AttributeName::WARNING;
    }
    return AttributeName::SUCCESS;

  }
}
