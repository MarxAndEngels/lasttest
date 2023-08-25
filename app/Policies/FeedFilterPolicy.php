<?php

namespace App\Policies;

use App\Complex\BasePolicy;
use App\Constants\Permission\FeedFilterPermission;
use App\Models\User;

class FeedFilterPolicy extends BasePolicy
{
  public function view(User $user): bool
  {
    return $user->can(FeedFilterPermission::VIEW);
  }

  public function viewAny(User $user): bool
  {
    return $user->can(FeedFilterPermission::VIEW);
  }
  public function create(User $user): bool
  {
    return $user->can(FeedFilterPermission::CREATE);
  }
  public function update(User $user): bool
  {
    return $user->can(FeedFilterPermission::UPDATE);
  }
  public function delete(User $user): bool
  {
    return $user->can(FeedFilterPermission::DELETE);
  }
}
