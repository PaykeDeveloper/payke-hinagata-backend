<?php

// FIXME: SAMPLE CODE

namespace App\Policies\Division;

use App\Models\Division\Division;
use App\Models\ModelType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Symfony\Component\HttpFoundation\Response;

class DivisionPolicy
{
    use HandlesAuthorization;

    private const MODEL = ModelType::division;

    public function viewAny(User $user): bool
    {
        if ($user->hasViewPermissionTo(self::MODEL)) {
            return true;
        }

        abort(Response::HTTP_NOT_FOUND);
    }

    public function view(User $user, Division $division): bool
    {
        $member = $user->findMember($division);
        if ($member?->hasViewOwnPermissionTo(self::MODEL)) {
            return true;
        }

        if ($user->hasViewAllPermissionTo(self::MODEL)) {
            return true;
        }

        abort(Response::HTTP_NOT_FOUND);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user)
            && $user->hasCreatePermissionTo(self::MODEL);
    }

    public function update(User $user, Division $division): bool
    {
        if (!$this->view($user, $division)) {
            return false;
        }

        $member = $user->findMember($division);
        if ($member?->hasUpdateOwnPermissionTo(self::MODEL)) {
            return true;
        }

        if ($user->hasUpdateAllPermissionTo(self::MODEL)) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Division $division): bool
    {
        if (!$this->view($user, $division)) {
            return false;
        }

        $member = $user->findMember($division);
        if ($member?->hasDeleteOwnPermissionTo(self::MODEL)) {
            return true;
        }

        if ($user->hasDeleteAllPermissionTo(self::MODEL)) {
            return true;
        }

        return false;
    }
}
