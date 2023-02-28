<?php

namespace Database\Factories\Common;

use App\Models\Common\Invitation;
use App\Models\Common\InvitationStatus;
use App\Models\Common\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InvitationFactory extends Factory
{
    protected $model = Invitation::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'email' => fake()->unique()->email,
            'token' => Str::random(60),
            'status' => fake()->randomElement(InvitationStatus::cases()),
            'role_names' => fake()->randomElements(UserRole::all()),
            'created_by' => fake()->boolean ? User::factory() : null,
        ];
    }

    public function pending(): InvitationFactory
    {
        return $this->state(fn (array $attributed) => [
            'status' => InvitationStatus::Pending,
        ]);
    }
}
