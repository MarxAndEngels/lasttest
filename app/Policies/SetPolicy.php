<?php

namespace App\Policies;

use App\Complex\BasePolicy;
use App\Constants\Permission\SetPermission;
use App\Models\User;

class SetPolicy extends BasePolicy
{
  public function view(User $user): bool
  {
    return $user->can(SetPermission::VIEW);
  }

  public function viewAny(User $user): bool
  {
    return $user->can(SetPermission::VIEW);
  }
  public function create(User $user): bool
  {
    return $user->can(SetPermission::CREATE);
  }
  public function update(User $user): bool
  {
    return $user->can(SetPermission::EDIT);
  }
  public function delete(User $user): bool
  {
    return $user->can(SetPermission::CREATE);
  }
}
