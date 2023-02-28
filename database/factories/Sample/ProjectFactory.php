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
        $startDate = fake()->optional()->date();
        $finishedAt = fake()->optional()->dateTimeBetween($startDate ?? '-30 years', '+6day')
            ?->setTimezone(new DateTimeZone('Asia/Tokyo'))->format(DATE_ATOM);

        return [
            'division_id' => Division::factory(),
            'member_id' => fn (array $attributes) => fake()->boolean() ?
                Member::factory(state: ['division_id' => $attributes['division_id']]) : null,
            'slug' => fake()->uuid,
            'name' => fake()->name,
            'description' => fake()->optional(default: '')->paragraph,
            'priority' => fake()->randomElement(Priority::cases()),
            'approved' => fake()->optional()->boolean,
            'start_date' => $startDate,
            'finished_at' => $finishedAt,
            'difficulty' => fake()->optional()->numberBetween(1, 5),
            'coefficient' => fake()->optional()->randomFloat(1, max: 99),
            'productivity' => fake()->optional()->randomFloat(3, max: 999999),
            'lock_version' => fake()->randomDigitNotNull,
        ];
    }

    public function configure(): self
    {
        return parent::configure()->afterCreating(function (Project $project) {
            if (!rand(0, 3)) {
                return;
            }

            $slug = fake()->slug;
            $thumbnail = UploadedFile::fake()->image(
                "$slug.png",
                fake()->numberBetween(10, 500),
                fake()->numberBetween(10, 500)
            );
            $project->saveCover($thumbnail);
        });
    }
}
