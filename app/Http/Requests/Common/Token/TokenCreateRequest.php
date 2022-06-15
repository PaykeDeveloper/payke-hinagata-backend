<?php

namespace App\Http\Requests\Common\Token;

use App\Models\Common\ClientApp;
use App\Models\Common\PlatformType;
use Illuminate\Validation\Rules\Enum;
use Laravel\Fortify\Http\Requests\LoginRequest;

class TokenCreateRequest extends LoginRequest
{
    public function rules(): array
    {
        return [
                'package_name' => ['required', new Enum(ClientApp::class)],
                'platform_type' => ['required', new Enum(PlatformType::class)],
                'device_id' => ['nullable', 'string'],
            ] + parent::rules();
    }
}
