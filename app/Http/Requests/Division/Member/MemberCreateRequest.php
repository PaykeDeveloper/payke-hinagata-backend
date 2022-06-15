<?php

// FIXME: SAMPLE CODE

namespace App\Http\Requests\Division\Member;

use App\Http\Requests\FormRequest;
use App\Models\Division\Division;
use App\Models\Division\MemberRole;
use App\Models\ModelType;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;

class MemberCreateRequest extends FormRequest
{
    public function rules(): array
    {
        /** @var Division $division */
        $division = $this->route('division');
        return [
            'user_id' => ['required', 'integer',
                Rule::unique('members')->where(function (Builder $query) use ($division) {
                    return $query->where('division_id', $division->id);
                }),
                Rule::exists('users', 'id')->where(function (Builder $query) use ($division) {
                    /** @var User $user */
                    $user = $this->user();
                    $member = $user->findMember($division);
                    $enableAll = $member?->hasCreateAllPermissionTo(ModelType::member)
                        || $user->hasCreateAllPermissionTo(ModelType::member);

                    return $enableAll ? $query : $query->where('id', $user->id);
                })],
            'role_names' => ['array'],
            'role_names.*' => ['string', Rule::exists('roles', 'name')->where(function (Builder $query) {
                return $query->whereIn('name', MemberRole::all());
            })]
        ];
    }
}
