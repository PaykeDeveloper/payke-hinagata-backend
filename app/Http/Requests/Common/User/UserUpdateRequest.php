<?php

namespace App\Http\Requests\Common\User;

use App\Http\Requests\FormRequest;
use App\Models\Common\UserRole;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
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
        return [
            'name' => ['string', 'max:255'],
            'role_names' => ['array'],
            'role_names.*' => [Rule::exists('roles', 'name')->where(function (Builder $query) {
                return $query->whereIn('name', UserRole::all());
            })]
        ];
    }
}
