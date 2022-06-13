<?php

// FIXME: SAMPLE CODE

namespace Database\Factories\Sample;

use App\Models\Division\Division;
use App\Models\Sample\Priority;
use App\Models\Sample\Project;
use DateTimeZone;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $startDate = $this->faker->date();
        $finishedAt = $this->faker->dateTimeBetween($startDate, '+6day')
            ->setTimezone(new DateTimeZone('Asia/Tokyo'))
            ->format(DATE_ATOM);

        return [
            'division_id' => Division::factory(),
            'slug' => $this->faker->uuid,
            'name' => $this->faker->name,
            'description' => $this->faker->paragraph(),
            'priority' => $this->faker->randomElement(Priority::cases()),
            'approved' => $this->faker->boolean(),
            'start_date' => $startDate,
            'finished_at' => $finishedAt,
            'difficulty' => $this->faker->numberBetween(1, 5),
            'coefficient' => $this->faker->randomFloat(1, max: 99),
            'productivity' => $this->faker->randomFloat(3, max: 999999),
            'lock_version' => $this->faker->randomDigit()
        ];
    }

    public function configure(): self
    {
        return parent::configure()->afterCreating(function (Project $project) {
            if (!rand(0, 3)) {
                return;
            }

            $thumbnail = UploadedFile::fake()->image(
                "{$this->faker->slug}.png",
                $this->faker->numberBetween(10, 500),
                $this->faker->numberBetween(10, 500)
            );
            $project->saveCover($thumbnail);
        });
    }
}
