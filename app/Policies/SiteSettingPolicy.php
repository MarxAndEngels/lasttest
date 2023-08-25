<?php

namespace App\Policies;

use App\Complex\BasePolicy;
use App\Constants\Permission\SiteSettingPermission;
use App\Models\User;

class SiteSettingPolicy extends BasePolicy
{
  public function view(User $user): bool
  {
    return $user->can(SiteSettingPermission::VIEW);
  }

  public function viewAny(User $user): bool
  {
    return $user->can(SiteSettingPermission::VIEW);
  }
  public function create(User $user): bool
  {
    return $user->can(SiteSettingPermission::CREATE);
  }
  public function update(User $user): bool
  {
    return $user->can(SiteSettingPermission::UPDATE);
  }
  public function delete(User $user): bool
  {
    return $user->can(SiteSettingPermission::DELETE);
  }
}
