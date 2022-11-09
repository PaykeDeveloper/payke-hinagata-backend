<?php

// FIXME: SAMPLE CODE

namespace Database\Factories\Sample;

use App\Models\Division\Division;
use App\Models\Division\Member;
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
        $startDate = $this->faker->optional()->date();
        $finishedAt = $this->faker->optional()->dateTimeBetween($startDate ?? '-30 years', '+6day')
            ?->setTimezone(new DateTimeZone('Asia/Tokyo'))->format(DATE_ATOM);

        return [
            'division_id' => Division::factory(),
            'member_id' => fn (array $attributes) => $this->faker->boolean() ?
                Member::factory(state: ['division_id' => $attributes['division_id']]) : null,
            'slug' => $this->faker->uuid,
            'name' => $this->faker->name,
            'description' => $this->faker->optional(default: '')->paragraph,
            'priority' => $this->faker->randomElement(Priority::cases()),
            'approved' => $this->faker->optional()->boolean,
            'start_date' => $startDate,
            'finished_at' => $finishedAt,
            'difficulty' => $this->faker->optional()->numberBetween(1, 5),
            'coefficient' => $this->faker->optional()->randomFloat(1, max: 99),
            'productivity' => $this->faker->optional()->randomFloat(3, max: 999999),
            'lock_version' => $this->faker->randomDigitNotNull,
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
