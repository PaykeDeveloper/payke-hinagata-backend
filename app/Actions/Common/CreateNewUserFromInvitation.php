<?php

namespace App\Actions\Common;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\Common\Invitation;
use App\Models\Common\InvitationStatus;
use App\Models\Common\Role;
use App\Models\Common\UserRole;
use App\Models\User;
use Illuminate\Database\Query\Builder;
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
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        $validatedInput = $this->validateInput($input);
        /** @var Invitation $invitation */
        $invitation = Invitation::find($validatedInput['id']);
        $user = User::create([
            'name' => $invitation->name,
            'email' => $invitation->email,
            'password' => Hash::make($validatedInput['password']),
            'locale' => request()->getPreferredLanguage(),
        ]);
        $roleNames = $this->filteredRoles($invitation->role_names);
        $user->syncRoles($roleNames);
        $invitation->approved();
        return $user;
    }

    /**
     * @throws ValidationException
     */
    private function validateInput(array $input): array
    {
        $updatedInput = $this->updateInput($input);
        $validator = Validator::make($updatedInput, [
            'password' => $this->passwordRules(),
            'id' => ['required', 'integer'],
            'token' => [
                'required',
                'string',
                Rule::exists(Invitation::class)->where(function (Builder $query) use ($updatedInput) {
                    return $query
                        ->where('id', '=', $updatedInput['id'] ?? null)
                        ->where('status', '=', InvitationStatus::Pending);
                }),
            ],
        ], messages: [
            'token.exists' => __('Register from an Invitation email.')
        ]);

        return $validator->validate();
    }

    private function updateInput(array $input): array
    {
        $updatedInput = $input;
        if (isset($input['token'])) {
            $updatedInput['token'] = hash('sha256', $input['token']);
        }
        return $updatedInput;
    }

    private function filteredRoles(array $roleNames): array
    {
        $userRoles = UserRole::all();
        $allRoles = Role::pluck('name')->all();
        return array_filter($roleNames, function ($name) use ($userRoles, $allRoles) {
            return in_array($name, $userRoles) && in_array($name, $allRoles);
        });
    }
}
