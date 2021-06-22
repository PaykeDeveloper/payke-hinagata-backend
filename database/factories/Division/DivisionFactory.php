<?php

// FIXME: SAMPLE CODE

namespace Database\Factories\Division;

use App\Models\Division\Division;
use Illuminate\Database\Eloquent\Factories\Factory;

class DivisionFactory extends Factory
{
    protected $model = Division::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
        ];
    }
}
