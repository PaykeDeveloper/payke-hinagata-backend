<?php

// FIXME: SAMPLE CODE

namespace App\Policies\Division;

use App\Models\Division\Division;
use App\Models\Division\Member;
use App\Models\ModelType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MemberPolicy
{
    use HandlesAuthorization;

    private const MODEL = ModelType::member;

    private Division $division;
    private ?Member $member;

    public function __construct(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        /** @var Division $division */
        $division = $request->route('division');
        $this->division = $division;
        $this->member = $user->findMember($division);
    }

    public function viewAny(User $user): bool
    {
        if ($this->member?->hasViewPermissionTo(self::MODEL)) {
            return true;
        }

        if ($user->hasViewAllPermissionTo(self::MODEL)) {
            return true;
        }

        abort(Response::HTTP_NOT_FOUND);
    }

    public function view(User $user, Member $member): bool
    {
        if ($this->division->id !== $member->division_id) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if ($this->member?->hasViewAllPermissionTo(self::MODEL)) {
            return true;
        }
        if (
            $this->member?->id === $member->id &&
            $this->member->hasViewOwnPermissionTo(self::MODEL)
        ) {
            return true;
        }

        if ($user->hasViewAllPermissionTo(self::MODEL)) {
            return true;
        }

        abort(Response::HTTP_NOT_FOUND);
    }

    public function create(User $user): bool
    {
        if (!$this->viewAny($user)) {
            return false;
        }

        if ($this->member?->hasCreatePermissionTo(self::MODEL)) {
            return true;
        }

        if ($user->hasCreateAllPermissionTo(self::MODEL)) {
            return true;
        }

        return false;
    }

    public function update(User $user, Member $member): bool
    {
        if (!$this->view($user, $member)) {
            return false;
        }

        if ($this->member?->hasUpdateAllPermissionTo(self::MODEL)) {
            return true;
        }
        if (
            $this->member?->id === $member->id &&
            $this->member->hasUpdateOwnPermissionTo(self::MODEL)
        ) {
            return true;
        }

        if ($user->hasUpdateAllPermissionTo(self::MODEL)) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Member $member): bool
    {
        if (!$this->view($user, $member)) {
            return false;
        }

        if ($this->member?->hasDeleteAllPermissionTo(self::MODEL)) {
            return true;
        }
        if (
            $this->member?->id === $member->id &&
            $this->member->hasDeleteOwnPermissionTo(self::MODEL)
        ) {
            return true;
        }
        if ($user->hasDeleteAllPermissionTo(self::MODEL)) {
            return true;
        }

        return false;
    }
}
