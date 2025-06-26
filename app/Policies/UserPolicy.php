<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function update(User $authenticatedUser, User $targetUser): bool
    {
        return $authenticatedUser->id === $targetUser->id ||
            ($authenticatedUser->isAdmin() && !$targetUser->isAdmin());
    }

    public function delete(User $authenticatedUser, User $targetUser): bool
    {
        return $authenticatedUser->id === $targetUser->id ||
            $authenticatedUser->isAdmin();
    }

}
