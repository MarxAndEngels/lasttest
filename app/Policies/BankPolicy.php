<?php

namespace App\Policies;

use App\Complex\BasePolicy;
use App\Constants\Permission\BankPermission;
use App\Models\User;

final class BankPolicy extends BasePolicy
{
  public function view(User $user): bool
  {
    return $user->can(BankPermission::VIEW);
  }

  public function viewAny(User $user): bool
  {
    return $user->can(BankPermission::VIEW);
  }
  public function create(User $user): bool
  {
    return $user->can(BankPermission::EDIT);
  }
  public function update(User $user): bool
  {
    return $user->can(BankPermission::EDIT);
  }
  public function delete(User $user): bool
  {
    return $user->can(BankPermission::EDIT);
  }
}
