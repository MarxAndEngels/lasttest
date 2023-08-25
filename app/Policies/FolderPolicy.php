<?php

namespace App\Policies;

use App\Complex\BasePolicy;
use App\Constants\Permission\FolderPermission;
use App\Models\User;

final class FolderPolicy extends BasePolicy
{
  public function view(User $user): bool
  {
    return $user->can(FolderPermission::VIEW);
  }

  public function viewAny(User $user): bool
  {
    return $user->can(FolderPermission::VIEW);
  }

  public function create(User $user): bool
  {
    return $user->can(FolderPermission::CREATE);
  }

  public function update(User $user): bool
  {
    return $user->can(FolderPermission::UPDATE);
  }

  public function delete(User $user): bool
  {
    return $user->can(FolderPermission::DELETE);
  }
}
