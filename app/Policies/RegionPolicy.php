<?php

namespace App\Policies;

use App\Complex\BasePolicy;
use App\Constants\Permission\RegionPermission;
use App\Models\User;

class RegionPolicy extends BasePolicy
{
  public function view(User $user): bool
  {
    return $user->can(RegionPermission::VIEW);
  }

  public function viewAny(User $user): bool
  {
    return $user->can(RegionPermission::VIEW);
  }
  public function create(User $user): bool
  {
    return $user->can(RegionPermission::CREATE);
  }
  public function update(User $user): bool
  {
    return $user->can(RegionPermission::EDIT);
  }
  public function delete(User $user): bool
  {
    return $user->can(RegionPermission::CREATE);
  }
}
