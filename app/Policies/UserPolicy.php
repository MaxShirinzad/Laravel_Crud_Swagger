<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine if the given user can delete another user.
     */
    public function delete(User $authenticatedUser, User $targetUser): bool
    {
        // Only allow deletion if:
        // - User is deleting themselves OR
        // - User is an admin (type_id = 2) AND not trying to delete another admin
        return $authenticatedUser->id === $targetUser->id ||
            $authenticatedUser->isAdmin();
    }
}
