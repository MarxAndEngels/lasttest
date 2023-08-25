<?php

namespace App\Policies;

use App\Complex\BasePolicy;
use App\Constants\Permission\PriceOldPermission;
use App\Models\User;

class PriceOldPolicy extends BasePolicy
{
  public function view(User $user): bool
  {
    return $user->can(PriceOldPermission::VIEW);
  }

  public function viewAny(User $user): bool
  {
    return $user->can(PriceOldPermission::VIEW);
  }
  public function create(User $user): bool
  {
    return $user->can(PriceOldPermission::CREATE);
  }
  public function update(User $user): bool
  {
    return $user->can(PriceOldPermission::EDIT);
  }
  public function delete(User $user): bool
  {
    return $user->can(PriceOldPermission::DELETE);
  }
}
