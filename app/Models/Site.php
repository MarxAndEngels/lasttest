<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Site extends Model
{
  use HasFactory;
  protected $table = 'sites';
  protected $guarded = [];
  public $timestamps = false;

  protected $fillable = [
    'favicon_image',
  ];

  public function dealer(): BelongsTo{
    return $this->belongsTo(Dealer::class);
  }
//    public function user(): hasMany{
//        return $this->hasMany(User::class);
//    }
  public function user(){
    return $this->belongsTo(User::class);
  }
  public function feed(){
    return $this->belongsToMany(Feed::class, 'site_feed', 'site_id', 'feed_id');
  }
}
