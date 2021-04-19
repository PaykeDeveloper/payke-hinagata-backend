<?php

// FIXME: SAMPLE CODE

namespace App\Http\Requests\Division\Member;

use App\Http\Requests\FormRequest;
use App\Models\Division\Division;
use App\Models\Division\Member;
use App\Models\Sample\MemberRole;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;

class MemberCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        /** @var User $user */
        $user = $this->user();
        /** @var Division $division */
        $division = $this->route('division');
        $member = $user && $division ? Member::findByUniqueKeys($user->id, $division->id) : null;
        $enable_all = $member?->hasAllCreatePermissionTo(Member::RESOURCE)
            || $user?->hasAllCreatePermissionTo(Member::RESOURCE);

        return [
            'user_id' => ['required', Rule::exists('users')->where(function (Builder $query) use ($enable_all, $user) {
                if ($enable_all) {
                    return $query;
                } else {
                    return $query->where('id', $user->id);
                }
            })],
            'role_names' => ['array'],
            'role_names.*' => ['string', Rule::exists('roles', 'name')->where(function (Builder $query) {
                return $query->whereIn('name', MemberRole::all());
            })]
        ];
    }
}
