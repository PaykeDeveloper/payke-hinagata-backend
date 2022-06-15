<?php

namespace App\Repositories\Division;

use App\Models\Division\Division;
use App\Models\Division\Member;
use App\Models\ModelType;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class MemberRepository
{
    public function index(User $user, Division $division): Collection
    {
        $members = $division->members->loadMissing(['permissions', 'roles']);
        $filterMyUser = fn(Member $member) => $member->user_id === $user->id;
        /** @var ?Member $member */
        $member = $members->firstWhere($filterMyUser);
        $enableAll = $member?->hasViewAllPermissionTo(ModelType::member)
            || $user->hasViewAllPermissionTo(ModelType::member);

        return match ($enableAll) {
            true => $members,
            false => $members->where($filterMyUser),
        };
    }

    public function store(array $attributes, Division $division): Member
    {
        $member = new Member($attributes);
        $member->division_id = $division->id;
        $member->save();
        if (array_key_exists('role_names', $attributes)) {
            $member->syncRoles($attributes['role_names']);
        }
        return $member->fresh();
    }

    public function update(array $attributes, Member $member): Member
    {
        $member->update($attributes);
        if (array_key_exists('role_names', $attributes)) {
            $member->syncRoles($attributes['role_names']);
        }
        return $member->fresh();
    }
}
