<?php

namespace App\Policies;

use App\Complex\BasePolicy;
use App\Constants\Permission\SitePermission;
use App\Models\User;

class SitePolicy extends BasePolicy
{
  public function view(User $user): bool
  {
    return $user->can(SitePermission::VIEW);
  }

  public function viewAny(User $user): bool
  {
    return $user->can(SitePermission::VIEW);
  }
  public function create(User $user): bool
  {
    return $user->can(SitePermission::EDIT);
  }
  public function update(User $user): bool
  {
    return $user->can(SitePermission::EDIT);
  }
  public function delete(User $user): bool
  {
    return $user->can(SitePermission::EDIT);
  }
}
