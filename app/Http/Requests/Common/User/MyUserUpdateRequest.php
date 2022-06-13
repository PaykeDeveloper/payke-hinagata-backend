<?php

namespace App\Http\Requests\Common\User;

use App\Http\Requests\FormRequest;
use App\Models\Common\LocaleType;
use Illuminate\Validation\Rules\Enum;

class MyUserUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'locale' => ['required', new Enum(LocaleType::class)],
        ];
    }
}
