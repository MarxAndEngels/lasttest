<?php

namespace App\Policies;

use App\Complex\BasePolicy;
use App\Constants\Permission\ArticlePermission;
use App\Models\User;

final class ArticleCategoryPolicy extends BasePolicy
{
  public function view(User $user): bool
  {
    return $user->can(ArticlePermission::VIEW);
  }

  public function viewAny(User $user): bool
  {
    return $user->can(ArticlePermission::VIEW);
  }

  public function create(User $user): bool
  {
    return $user->can(ArticlePermission::CREATE);
  }

  public function update(User $user): bool
  {
    return $user->can(ArticlePermission::UPDATE);
  }

  public function delete(User $user): bool
  {
    return $user->can(ArticlePermission::DELETE);
  }
}
