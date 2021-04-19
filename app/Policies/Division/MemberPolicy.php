<?php

// FIXME: SAMPLE CODE

namespace App\Policies\Division;

use App\Models\Division\Division;
use App\Models\Division\Member;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MemberPolicy
{
    use HandlesAuthorization;

    private const RESOURCE = Member::RESOURCE;

    private Division $division;
    private ?Member $member;

    public function __construct(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        /** @var Division $division */
        $division = $request->route('division');
        $this->division = $division;
        $this->member = Member::findByUniqueKeys($user->id, $division->id);
    }

    public function viewAny(User $user): bool
    {
        if ($this->member?->hasViewPermissionTo(self::RESOURCE)) {
            return true;
        }

        if ($user->hasAllViewPermissionTo(self::RESOURCE)) {
            return true;
        }

        abort(Response::HTTP_NOT_FOUND);
        return false;
    }

    public function view(User $user, Member $member): bool
    {
        if ($this->division->id !== $member->division_id) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if ($this->member?->hasAllViewPermissionTo(self::RESOURCE)) {
            return true;
        }
        if ($this->member?->hasOwnViewPermissionTo(self::RESOURCE)) {
            if ($this->member->id === $member->id) {
                return true;
            }
        }

        if ($user->hasAllViewPermissionTo(self::RESOURCE)) {
            return true;
        }

        abort(Response::HTTP_NOT_FOUND);
        return false;
    }

    public function create(User $user): bool
    {
        if (!$this->viewAny($user)) {
            return false;
        }

        if ($this->member?->hasCreatePermissionTo(self::RESOURCE)) {
            return true;
        }

        if ($user->hasAllCreatePermissionTo(self::RESOURCE)) {
            return true;
        }

        return false;
    }

    public function update(User $user, Member $member): bool
    {
        if (!$this->view($user, $member)) {
            return false;
        }

        if ($this->member?->hasAllUpdatePermissionTo(self::RESOURCE)) {
            return true;
        }
        if ($this->member?->hasOwnUpdatePermissionTo(self::RESOURCE)) {
            if ($this->member->id === $member->id) {
                return true;
            }
        }

        if ($user->hasAllUpdatePermissionTo(self::RESOURCE)) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Member $member): bool
    {
        if (!$this->view($user, $member)) {
            return false;
        }

        if ($this->member?->hasAllDeletePermissionTo(self::RESOURCE)) {
            return true;
        }
        if ($this->member?->hasOwnDeletePermissionTo(self::RESOURCE)) {
            if ($this->member->id === $member->id) {
                return true;
            }
        }
        if ($user->hasAllDeletePermissionTo(self::RESOURCE)) {
            return true;
        }

        return false;
    }
}
