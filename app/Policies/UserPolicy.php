<?php

namespace App\Policies;

use App\Complex\BasePolicy;
use App\Constants\Permission\UserPermission;
use App\Models\User;

class UserPolicy extends BasePolicy
{
  public function view(User $user): bool
  {
    return $user->can(UserPermission::VIEW);
  }
  public function viewAny(User $user): bool
  {
    return $user->can(UserPermission::VIEW);
  }
  public function create(User $user): bool
  {
    return $user->can(UserPermission::EDIT);
  }
  public function update(User $user): bool
  {
    return $user->can(UserPermission::EDIT);
  }
  public function delete(User $user): bool
  {
    return $user->can(UserPermission::EDIT);
  }
}
