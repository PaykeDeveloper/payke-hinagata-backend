<?php

namespace App\Http\Requests\Auth\Invitation;

use App\Http\Requests\FormRequest;
use App\Models\User;
use Illuminate\Validation\Rule;

class InvitationCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique(User::class),
                ],
                'locale' => ['required', 'string']
            ] + parent::rules();
    }
}
