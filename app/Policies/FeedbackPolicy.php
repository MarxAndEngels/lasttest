<?php

namespace App\Policies;

use App\Complex\BasePolicy;
use App\Constants\Permission\FeedbackPermission;
use App\Models\User;

final class FeedbackPolicy extends BasePolicy
{
  public function view(User $user): bool
  {
    return $user->can(FeedbackPermission::VIEW);
  }

  public function viewAny(User $user): bool
  {
    return $user->can(FeedbackPermission::VIEW);
  }

  public function create(User $user): bool
  {
    return $user->can(FeedbackPermission::CREATE);
  }

  public function update(User $user): bool
  {
    return $user->can(FeedbackPermission::UPDATE);
  }

  public function delete(User $user): bool
  {
    return $user->can(FeedbackPermission::DELETE);
  }
}
