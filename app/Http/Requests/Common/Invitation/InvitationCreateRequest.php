<?php

namespace App\Http\Requests\Common\Invitation;

use App\Http\Requests\FormRequest;
use App\Models\Common\Invitation;
use App\Models\Common\LocaleType;
use App\Models\Common\UserRole;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class InvitationCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
                Rule::unique(Invitation::class),
            ],
            'locale' => ['required', new Enum(LocaleType::class)],
            'role_names' => ['present', 'array'],
            'role_names.*' => ['string', Rule::in(UserRole::all())],
        ];
    }
}
