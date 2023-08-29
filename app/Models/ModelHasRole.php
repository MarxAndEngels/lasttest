<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelHasRole extends Model
{
    use HasFactory;

  protected $table = 'model_has_roles';
  protected $guarded = [];
  public $timestamps = true;
  protected $attributes = [
    'model_type' => 'App\Models\User',
  ];

  protected $primaryKey = 'role_id';
  public $incrementing = false;
  public function role(){
    return $this->belongsTo(Role::class, 'role_id', 'id');
  }
  public function user(){
    return $this->belongsTo(User::class, 'model_id', 'id');
  }
}
