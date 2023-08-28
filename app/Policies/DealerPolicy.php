<?php

namespace App\Policies;

use App\Complex\BasePolicy;
use App\Constants\Permission\DealerPermission;
use App\Models\User;

class DealerPolicy extends BasePolicy
{
  public function view(User $user): bool
  {
    return true;
  }

  public function viewAny(User $user): bool
  {
    return true;
  }
  public function create(User $user): bool
  {
    return true;
  }
  public function update(User $user): bool
  {
    return true;
  }
  public function delete(User $user): bool
  {
    return true;
  }
}
