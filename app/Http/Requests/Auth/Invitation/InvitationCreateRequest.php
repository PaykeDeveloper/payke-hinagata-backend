<?php

namespace App\Http\Requests\Auth\Invitation;

use App\Http\Requests\FormRequest;
use App\Models\Auth\Invitation;
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
        $user = $this->user();
        return [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
                Rule::unique(Invitation::class)->where(function ($query) use ($user) {
                    return $query->where('user_id', $user->id);
                }),
            ],
            'locale' => ['required', 'string']
        ];
    }
}