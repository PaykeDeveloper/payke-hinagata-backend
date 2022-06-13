<?php

namespace App\Repositories\Division;

use App\Models\Division\Division;
use App\Models\Division\Member;
use App\Models\Division\MemberRole;
use App\Models\ModelType;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class DivisionRepository
{

    public function index(User $user): Collection
    {
        return match ($user->hasViewAllPermissionTo(ModelType::division)) {
            true => Division::all(),
            false => Division::query()->whereHas('members', function (Builder $query) use ($user) {
                $query->where('user_id', '=', $user->id);
            })->get(),
        };
    }

    public function store(array $attributes, User $user): Division
    {
        $division = Division::create($attributes);

        $member = Member::create([
            'user_id' => $user->id,
            'division_id' => $division->id,
        ]);
        $member->syncRoles(MemberRole::all());

        return $division->fresh();
    }

    public function update(array $attributes, Division $division): Division
    {
        $division->update($attributes);
        return $division->fresh();
    }
}
