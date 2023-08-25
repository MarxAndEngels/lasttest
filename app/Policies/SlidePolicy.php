<?php

namespace App\Policies;

use App\Complex\BasePolicy;
use App\Constants\Permission\SlidePermission;
use App\Models\User;

final class SlidePolicy extends BasePolicy
{
  public function view(User $user): bool
  {
    return $user->can(SlidePermission::VIEW);
  }

  public function viewAny(User $user): bool
  {
    return $user->can(SlidePermission::VIEW);
  }
  public function create(User $user): bool
  {
    return $user->can(SlidePermission::CREATE);
  }
  public function update(User $user): bool
  {
    return $user->can(SlidePermission::UPDATE);
  }
  public function delete(User $user): bool
  {
    return $user->can(SlidePermission::DELETE);
  }
}
