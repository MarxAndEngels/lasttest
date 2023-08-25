<?php

namespace App\Policies;

use App\Complex\BasePolicy;
use App\Constants\Permission\MarkPermission;
use App\Models\User;

final class MarkPolicy extends BasePolicy
{
  public function view(User $user): bool
  {
    return $user->can(MarkPermission::VIEW);
  }

  public function viewAny(User $user): bool
  {
    return $user->can(MarkPermission::VIEW);
  }

  public function create(User $user): bool
  {
    return $user->can(MarkPermission::CREATE);
  }

  public function update(User $user): bool
  {
    return $user->can(MarkPermission::UPDATE);
  }

  public function delete(User $user): bool
  {
    return $user->can(MarkPermission::DELETE);
  }
}
