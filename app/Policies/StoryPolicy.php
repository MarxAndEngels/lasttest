<?php

namespace App\Policies;

use App\Complex\BasePolicy;
use App\Constants\Permission\StoryPermission;
use App\Models\User;

final class StoryPolicy extends BasePolicy
{
  public function view(User $user): bool
  {
    return $user->can(StoryPermission::VIEW);
  }

  public function viewAny(User $user): bool
  {
    return $user->can(StoryPermission::VIEW);
  }
  public function create(User $user): bool
  {
    return $user->can(StoryPermission::EDIT);
  }
  public function update(User $user): bool
  {
    return $user->can(StoryPermission::EDIT);
  }
  public function delete(User $user): bool
  {
    return $user->can(StoryPermission::EDIT);
  }
}
