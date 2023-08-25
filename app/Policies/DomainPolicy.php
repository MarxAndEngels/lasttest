<?php

namespace App\Policies;

use App\Complex\BasePolicy;
use App\Constants\Permission\DomainPermission;
use App\Models\User;

final class DomainPolicy extends BasePolicy
{
  public function view(User $user): bool
  {
    return $user->can(DomainPermission::VIEW);
  }

  public function viewAny(User $user): bool
  {
    return $user->can(DomainPermission::VIEW);
  }
  public function create(User $user): bool
  {
    return false;
  }
  public function update(User $user): bool
  {
    return false;
  }
  public function delete(User $user): bool
  {
    return false;
  }
}
