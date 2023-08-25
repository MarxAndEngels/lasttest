<?php

namespace App\Policies;

use App\Complex\BasePolicy;
use App\Constants\Permission\DealerPermission;
use App\Models\User;

class DealerPolicy extends BasePolicy
{
  public function view(User $user): bool
  {
    return $user->can(DealerPermission::VIEW);
  }

  public function viewAny(User $user): bool
  {
    return $user->can(DealerPermission::VIEW);
  }
  public function create(User $user): bool
  {
    return $user->can(DealerPermission::CREATE);
  }
  public function update(User $user): bool
  {
    return $user->can(DealerPermission::EDIT);
  }
  public function delete(User $user): bool
  {
    return $user->can(DealerPermission::CREATE);
  }
}
