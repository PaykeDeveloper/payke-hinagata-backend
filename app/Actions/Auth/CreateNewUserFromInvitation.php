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
        $merged_input = $input;
        if (isset($merged_input['token'])) {
            $merged_input['token'] = hash('sha256', $merged_input['token']);
        }
        $validated_input = Validator::make($merged_input, [
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
                Rule::exists(Invitation::class)->where(function ($query) use ($merged_input) {
                    return $query
                        ->where('email', $merged_input['email'] ?? null);
                }),
            ],
        ])->validate();

        $user = User::create([
            'name' => $validated_input['name'],
            'email' => $validated_input['email'],
            'password' => Hash::make($validated_input['password']),
            'locale' => request()->getPreferredLanguage(),
        ]);
        $user->markEmailAsVerified();
        return $user;
    }
}
