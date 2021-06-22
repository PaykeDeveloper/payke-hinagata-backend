<?php

// FIXME: SAMPLE CODE

namespace App\Http\Requests\Division\Member;

use App\Http\Requests\FormRequest;
use App\Models\Division\MemberRole;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;

class MemberUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'role_names' => ['array'],
            'role_names.*' => ['string', Rule::exists('roles', 'name')->where(function (Builder $query) {
                return $query->whereIn('name', MemberRole::all());
            })]
        ];
    }
}
