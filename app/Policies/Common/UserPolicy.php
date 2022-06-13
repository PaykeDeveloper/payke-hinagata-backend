<?php

namespace App\Policies\Common;

use App\Models\ModelType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Symfony\Component\HttpFoundation\Response;

class UserPolicy
{
    use HandlesAuthorization;

    private const MODEL = ModelType::user;

    public function viewAny(User $user): bool
    {
        if ($user->hasViewPermissionTo(self::MODEL)) {
            return true;
        }

        abort(Response::HTTP_NOT_FOUND);
    }

    public function view(User $user, User $targetUser): bool
    {
        if ($user->hasViewAllPermissionTo(self::MODEL)) {
            return true;
        }
        if (
            $user->id === $targetUser->id &&
            $user->hasViewOwnPermissionTo(self::MODEL)
        ) {
            return true;
        }

        abort(Response::HTTP_NOT_FOUND);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user)
            && $user->hasCreateAllPermissionTo(self::MODEL);
    }

    public function update(User $user, User $targetUser): bool
    {
        if (!$this->view($user, $targetUser)) {
            return false;
        }

        if (
            $user->id === $targetUser->id &&
            $user->hasUpdateOwnPermissionTo(self::MODEL)
        ) {
            return true;
        }

        if ($user->hasUpdateAllPermissionTo(self::MODEL)) {
            return true;
        }

        return false;
    }

    public function delete(User $user, User $targetUser): bool
    {
        if (!$this->view($user, $targetUser)) {
            return false;
        }

        if (
            $user->id === $targetUser->id &&
            $user->hasDeleteOwnPermissionTo(self::MODEL)
        ) {
            return true;
        }

        if ($user->hasDeleteAllPermissionTo(self::MODEL)) {
            return true;
        }

        return false;
    }
}
