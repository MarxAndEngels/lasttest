<?php

namespace App\Policies;

use App\Complex\BasePolicy;
use App\Constants\Permission\OfferPermission;
use App\Models\User;

final class OfferPolicy extends BasePolicy
{
  public function view(User $user): bool
  {
    return $user->can(OfferPermission::VIEW);
  }

  public function viewAny(User $user): bool
  {
    return $user->can(OfferPermission::VIEW);
  }

  public function create(User $user): bool
  {
    return $user->can(OfferPermission::CREATE);
  }

  public function update(User $user): bool
  {
    return $user->can(OfferPermission::UPDATE);
  }

  public function delete(User $user): bool
  {
    return $user->can(OfferPermission::DELETE);
  }
}
