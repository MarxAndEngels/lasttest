<?php

namespace App\Policies;

use App\Complex\BasePolicy;
use App\Constants\Permission\TelegramChannelPermission;
use App\Models\User;

final class TelegramChannelPolicy extends BasePolicy
{
  public function view(User $user): bool
  {
    return $user->can(TelegramChannelPermission::VIEW);
  }

  public function viewAny(User $user): bool
  {
    return $user->can(TelegramChannelPermission::VIEW);
  }
  public function create(User $user): bool
  {
    return $user->can(TelegramChannelPermission::CREATE);
  }
  public function update(User $user): bool
  {
    return $user->can(TelegramChannelPermission::UPDATE);
  }
  public function delete(User $user): bool
  {
    return $user->can(TelegramChannelPermission::DELETE);
  }
}
