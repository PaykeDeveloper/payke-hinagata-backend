<?php

// FIXME: SAMPLE CODE

namespace App\Policies\Sample;

use App\Models\Division\Division;
use App\Models\Division\Member;
use App\Models\Sample\DivisionProject;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DivisionProjectPolicy
{
    use HandlesAuthorization;

    private const RESOURCE = DivisionProject::RESOURCE;

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
        if ($this->member?->hasAllViewPermissionTo(self::RESOURCE)) {
            return true;
        }

        if ($user->hasAllViewPermissionTo(self::RESOURCE)) {
            return true;
        }

        abort(Response::HTTP_NOT_FOUND);
        return false;
    }

    public function view(User $user, Project $project): bool
    {
        if ($this->division->id !== $project->division_id) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if ($this->member?->hasAllViewPermissionTo(self::RESOURCE)) {
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
        if (!$this->viewAny($user)) {
            return false;
        }

        if ($this->member?->hasAllCreatePermissionTo(self::RESOURCE)) {
            return true;
        }

        if ($user->hasAllCreatePermissionTo(self::RESOURCE)) {
            return true;
        }

        return false;
    }

    public function update(User $user, Project $project): bool
    {
        if (!$this->view($user, $project)) {
            return false;
        }

        if ($this->member?->hasAllUpdatePermissionTo(self::RESOURCE)) {
            return true;
        }

        if ($user->hasAllUpdatePermissionTo(self::RESOURCE)) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Project $project): bool
    {
        if (!$this->view($user, $project)) {
            return false;
        }

        if ($this->member?->hasAllDeletePermissionTo(self::RESOURCE)) {
            return true;
        }

        if ($user->hasAllDeletePermissionTo(self::RESOURCE)) {
            return true;
        }

        return false;
    }
}
