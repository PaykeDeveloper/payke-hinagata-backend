<?php

// FIXME: SAMPLE CODE

namespace Database\Factories\Sample;

use App\Models\Division\Division;
use App\Models\Sample\Priority;
use App\Models\Sample\Project;
use DateTimeZone;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $start_date = $this->faker->date();
        $finished_at = $this->faker->dateTimeBetween($start_date, '+6day')
            ->setTimezone(new DateTimeZone('Asia/Tokyo'))
            ->format(DATE_ATOM);

        return [
            'division_id' => Division::factory(),
            'slug' => $this->faker->uuid,
            'name' => $this->faker->name,
            'description' => $this->faker->paragraph(),
            'priority' => $this->faker->randomElement(Priority::all()),
            'approved' => $this->faker->boolean(),
            'start_date' => $start_date,
            'finished_at' => $finished_at,
            'difficulty' => $this->faker->numberBetween(1, 5),
            'coefficient' => $this->faker->randomFloat(1, max: 99),
            'productivity' => $this->faker->randomFloat(3, max: 999999),
            'lock_version' => $this->faker->randomDigit()
        ];
    }
}
