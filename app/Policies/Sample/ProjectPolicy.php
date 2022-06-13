<?php

// FIXME: SAMPLE CODE

namespace App\Policies\Sample;

use App\Models\Division\Division;
use App\Models\Division\Member;
use App\Models\ModelType;
use App\Models\Sample\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectPolicy
{
    use HandlesAuthorization;

    private const MODEL = ModelType::project;

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
        if ($this->member?->hasViewAllPermissionTo(self::MODEL)) {
            return true;
        }

        if ($user->hasViewAllPermissionTo(self::MODEL)) {
            return true;
        }

        abort(Response::HTTP_NOT_FOUND);
    }

    public function view(User $user, Project $project): bool
    {
        if ($this->division->id !== $project->division_id) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if ($this->member?->hasViewAllPermissionTo(self::MODEL)) {
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

        if ($this->member?->hasCreateAllPermissionTo(self::MODEL)) {
            return true;
        }

        if ($user->hasCreateAllPermissionTo(self::MODEL)) {
            return true;
        }

        return false;
    }

    public function update(User $user, Project $project): bool
    {
        if (!$this->view($user, $project)) {
            return false;
        }

        if ($this->member?->hasUpdateAllPermissionTo(self::MODEL)) {
            return true;
        }

        if ($user->hasUpdateAllPermissionTo(self::MODEL)) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Project $project): bool
    {
        if (!$this->view($user, $project)) {
            return false;
        }

        if ($this->member?->hasDeleteAllPermissionTo(self::MODEL)) {
            return true;
        }

        if ($user->hasDeleteAllPermissionTo(self::MODEL)) {
            return true;
        }

        return false;
    }
}
