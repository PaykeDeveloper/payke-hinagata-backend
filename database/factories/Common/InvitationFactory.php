<?php

namespace Database\Factories\Common;

use App\Models\Common\Invitation;
use App\Models\Common\InvitationStatus;
use App\Models\Common\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InvitationFactory extends Factory
{
    protected $model = Invitation::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->email,
            'token' => Str::random(60),
            'status' => $this->faker->randomElement(InvitationStatus::cases()),
            'role_names' => $this->faker->randomElements(UserRole::all()),
            'created_by' => null,
        ];
    }

    public function pending(): InvitationFactory
    {
        return $this->state(fn(array $attributed) => [
            'status' => InvitationStatus::Pending,
        ]);
    }
}
