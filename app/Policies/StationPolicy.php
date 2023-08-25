<?php

namespace App\Policies;

use App\Complex\BasePolicy;
use App\Constants\Permission\StationPermission;
use App\Models\User;

final class StationPolicy extends BasePolicy
{
  public function view(User $user): bool
  {
    return $user->can(StationPermission::VIEW);
  }

  public function viewAny(User $user): bool
  {
    return $user->can(StationPermission::VIEW);
  }
  public function create(User $user): bool
  {
    return $user->can(StationPermission::EDIT);
  }
  public function update(User $user): bool
  {
    return $user->can(StationPermission::EDIT);
  }
  public function delete(User $user): bool
  {
    return $user->can(StationPermission::EDIT);
  }
}
