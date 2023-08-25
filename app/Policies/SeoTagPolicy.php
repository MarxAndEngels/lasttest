<?php

namespace App\Policies;

use App\Complex\BasePolicy;
use App\Constants\Permission\SeoTagPermission;
use App\Models\User;

class SeoTagPolicy extends BasePolicy
{
  public function view(User $user): bool
  {
    return $user->can(SeoTagPermission::VIEW);
  }

  public function viewAny(User $user): bool
  {
    return $user->can(SeoTagPermission::VIEW);
  }
  public function create(User $user): bool
  {
    return $user->can(SeoTagPermission::CREATE);
  }
  public function update(User $user): bool
  {
    return $user->can(SeoTagPermission::EDIT);
  }
  public function delete(User $user): bool
  {
    return $user->can(SeoTagPermission::DELETE);
  }
}
