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
        $invitation = Invitation::find($validated_input['id']);
        $user = User::create([
            'name' => $validated_input['name'],
            'email' => $invitation->email,
            'password' => Hash::make($validated_input['password']),
            'locale' => request()->getPreferredLanguage(),
        ]);
        $invitation->approved();
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
            'password' => $this->passwordRules(),
            'id' => ['required', 'integer'],
            'token' => [
                'required',
                'string',
                Rule::exists(Invitation::class)->where(function ($query) use ($updated_input) {
                    return $query
                        ->where('id', $updated_input['id'] ?? null)
                        ->where('status', InvitationStatus::PENDING);
                }),
            ],
        ], messages: [
            'token.exists' => __('Register from an Invitation email.')
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
