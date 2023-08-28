<?php

namespace App\Complex;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

abstract class BasePolicy
{
  use HandlesAuthorization;

  public function before(User $user): ?bool
  {
//    if ($user->isRoot()) {
      return true;
//    }
//    return null;
  }
}
