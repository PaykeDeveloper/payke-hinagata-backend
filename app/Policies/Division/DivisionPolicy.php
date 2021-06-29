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
        if ($member?->hasViewOwnPermissionTo(self::RESOURCE)) {
            return true;
        }

        if ($user->hasViewAllPermissionTo(self::RESOURCE)) {
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
        if ($member?->hasUpdateOwnPermissionTo(self::RESOURCE)) {
            return true;
        }

        if ($user->hasUpdateAllPermissionTo(self::RESOURCE)) {
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
        if ($member?->hasDeleteOwnPermissionTo(self::RESOURCE)) {
            return true;
        }

        if ($user->hasDeleteAllPermissionTo(self::RESOURCE)) {
            return true;
        }

        return false;
    }

    private ?Member $member = null;
    private ?int $userId = null;
    private ?int $divisionId = null;

    private function findMember(int $userId, int $divisionId): ?Member
    {
        if ($userId === $this->userId && $divisionId === $this->divisionId) {
            return $this->member;
        }

        $this->userId = $userId;
        $this->divisionId = $divisionId;
        $this->member = Member::findByUniqueKeys($userId, $divisionId);
        return $this->member;
    }
}
