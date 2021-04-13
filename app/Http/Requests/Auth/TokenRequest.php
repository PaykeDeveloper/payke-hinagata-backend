<?php

namespace App\Http\Requests\Auth;

use App\Models\Common\ClientApp;
use App\Models\Common\PlatformType;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Http\Requests\LoginRequest;

class TokenRequest extends LoginRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
                'package_name' => ['required', Rule::in(ClientApp::all())],
                'platform_type' => ['required', Rule::in(PlatformType::all())],
                'device_id' => ['nullable', 'string'],
            ] + parent::rules();
    }
}
