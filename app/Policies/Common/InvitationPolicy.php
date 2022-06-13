<?php

/** @noinspection PhpUnusedParameterInspection */

namespace App\Policies\Common;

use App\Models\Common\Invitation;
use App\Models\ModelType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Symfony\Component\HttpFoundation\Response;

class InvitationPolicy
{
    use HandlesAuthorization;

    private const MODEL = ModelType::invitation;

    public function viewAny(User $user): bool
    {
        if ($user->hasViewAllPermissionTo(self::MODEL)) {
            return true;
        }

        abort(Response::HTTP_NOT_FOUND);
    }

    public function view(User $user, Invitation $invitation): bool
    {
        if ($user->hasViewAllPermissionTo(self::MODEL)) {
            return true;
        }

        abort(Response::HTTP_NOT_FOUND);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user)
            && $user->hasCreateAllPermissionTo(self::MODEL);
    }

    public function update(User $user, Invitation $invitation): bool
    {
        if (!$this->view($user, $invitation)) {
            return false;
        }

        if ($user->hasUpdateAllPermissionTo(self::MODEL)) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Invitation $invitation): bool
    {
        if (!$this->view($user, $invitation)) {
            return false;
        }

        if ($user->hasDeleteAllPermissionTo(self::MODEL)) {
            return true;
        }

        return false;
    }
}
