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
        if ($this->member) {
            return $this->member->hasViewAllPermissionTo(self::MODEL);
        }
        return $user->hasViewAllPermissionTo(self::MODEL);
    }

    public function view(User $user, Project $project): bool
    {
        $this->assertNotFound($project);
        if ($this->member) {
            return $this->member->hasViewAllPermissionTo(self::MODEL);
        }
        return $user->hasViewAllPermissionTo(self::MODEL);
    }

    public function create(User $user): bool
    {
        if ($this->member) {
            return $this->member->hasCreateAllPermissionTo(self::MODEL);
        }
        return $user->hasCreateAllPermissionTo(self::MODEL);
    }

    public function update(User $user, Project $project): bool
    {
        $this->assertNotFound($project);
        if ($this->member) {
            return $this->member->hasUpdateAllPermissionTo(self::MODEL);
        }
        return $user->hasUpdateAllPermissionTo(self::MODEL);
    }

    public function delete(User $user, Project $project): bool
    {
        $this->assertNotFound($project);
        if ($this->member) {
            return $this->member->hasDeleteAllPermissionTo(self::MODEL);
        }
        return $user->hasDeleteAllPermissionTo(self::MODEL);
    }

    private function assertNotFound(Project $project): void
    {
        if ($this->division->id !== $project->division_id) {
            abort(Response::HTTP_NOT_FOUND);
        }
    }
}
