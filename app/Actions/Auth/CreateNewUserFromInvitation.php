<?php

namespace App\Actions\Auth;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\Auth\Invitation;
use App\Models\Auth\InvitationStatus;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUserFromInvitation implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param array $input
     * @return User
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        $validated_input = $this->validateInput($input);
        $user = User::create([
            'name' => $validated_input['name'],
            'email' => $validated_input['email'],
            'password' => Hash::make($validated_input['password']),
            'locale' => request()->getPreferredLanguage(),
        ]);
        $user->markEmailAsVerified();
        return $user;
    }

    /**
     * @throws ValidationException
     */
    private function validateInput(array $input): array
    {
        $updated_input = $this->updateInput($input);
        $validator = Validator::make($updated_input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
                Rule::exists(Invitation::class)->where(function ($query) {
                    return $query->where('status', InvitationStatus::PENDING);
                }),
            ],
            'password' => $this->passwordRules(),
            'token' => [
                'required',
                Rule::exists(Invitation::class)->where(function ($query) use ($updated_input) {
                    return $query
                        ->where('email', $updated_input['email'] ?? null);
                }),
            ],
        ], messages: [
            'email.exists' => __('Email Address Needed for an Invitation.')
        ]);

        return $validator->validate();
    }

    private function updateInput(array $input): array
    {
        $updated_input = $input;
        if (isset($input['token'])) {
            $updated_input['token'] = hash('sha256', $input['token']);
        }
        return $updated_input;
    }
}
