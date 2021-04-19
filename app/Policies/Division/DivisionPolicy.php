<?php

// FIXME: SAMPLE CODE

namespace App\Policies\Division;

use App\Models\Division\Division;
use App\Models\Division\Member;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Response;

class DivisionPolicy
{
    use HandlesAuthorization;

    private const RESOURCE = Division::RESOURCE;

    public function viewAny(User $user): bool
    {
        if ($user->hasViewPermissionTo(self::RESOURCE)) {
            return true;
        }

        abort(Response::HTTP_NOT_FOUND);
        return false;
    }

    public function view(User $user, Division $division): bool
    {
        $member = $this->findMember($user->id, $division->id);
        if ($member?->hasOwnViewPermissionTo(self::RESOURCE)) {
            return true;
        }

        if ($user->hasAllViewPermissionTo(self::RESOURCE)) {
            return true;
        }

        abort(Response::HTTP_NOT_FOUND);
        return false;
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user)
            && $user->hasCreatePermissionTo(self::RESOURCE);
    }

    public function update(User $user, Division $division): bool
    {
        if (!$this->view($user, $division)) {
            return false;
        }

        $member = $this->findMember($user->id, $division->id);
        if ($member?->hasOwnUpdatePermissionTo(self::RESOURCE)) {
            return true;
        }

        if ($user->hasAllUpdatePermissionTo(self::RESOURCE)) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Division $division): bool
    {
        if (!$this->view($user, $division)) {
            return false;
        }

        $member = $this->findMember($user->id, $division->id);
        if ($member?->hasOwnDeletePermissionTo(self::RESOURCE)) {
            return true;
        }

        if ($user->hasAllDeletePermissionTo(self::RESOURCE)) {
            return true;
        }

        return false;
    }

    public function restore(User $user, Division $division): bool
    {
        return false;
    }

    public function forceDelete(User $user, Division $division): bool
    {
        return false;
    }

    private ?Member $member = null;
    private ?string $user_id = null;
    private ?string $division_id = null;

    private function findMember(string $user_id, string $division_id): ?Member
    {
        if ($user_id === $this->user_id && $division_id === $this->division_id) {
            return $this->member;
        }

        $this->user_id = $user_id;
        $this->division_id = $division_id;
        $this->member = Member::findByUniqueKeys($user_id, $division_id);
        return $this->member;
    }
}
