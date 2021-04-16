<?php

namespace App\Http\Requests\Common\User;

use App\Models\Common\UserRole;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends UserShowRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255'],
            'roles' => ['array'],
            'roles.*' => [Rule::exists('roles', 'name')->where(function (Builder $query) {
                return $query->whereIn('name', UserRole::all());
            })]
        ];
    }
}
