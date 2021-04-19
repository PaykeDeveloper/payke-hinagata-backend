<?php

// FIXME: SAMPLE CODE

namespace App\Policies\Sample;

use App\Models\Division\Division;
use App\Models\Division\Member;
use App\Models\Sample\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectPolicy
{
    use HandlesAuthorization;

    private const RESOURCE = Project::RESOURCE;

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
        return $this->viewAny($user)
            && $user->hasAllCreatePermissionTo(self::RESOURCE);
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

    public function restore(User $user, Project $project): bool
    {
        return false;
    }

    public function forceDelete(User $user, Project $project): bool
    {
        return false;
    }
}
