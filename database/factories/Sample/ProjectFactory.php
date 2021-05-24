<?php

// FIXME: SAMPLE CODE

namespace Database\Factories\Sample;

use App\Models\Division\Division;
use App\Models\Sample\FooBar;
use App\Models\Sample\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $publish_date = $this->faker->date();
        $approved_at = $this->faker->dateTimeBetween($publish_date, '+6day')
            ->setTimezone(new \DateTimeZone('Asia/Tokyo'))
            ->format(DATE_ATOM);

        return [
            'division_id' => Division::factory(),
            'name' => $this->faker->name,
            'confirmed' => $this->faker->boolean(),
            'publish_date' => $publish_date,
            'approved_at' => $approved_at,
            'amount' => strval($this->faker->randomFloat(1, max: 99)),
            'column' => $this->faker->randomFloat(3, max: 999999),
            'choices' => $this->faker->randomElement(FooBar::all()),
            'description' => $this->faker->paragraph(),
            'votes' => $this->faker->numberBetween(1, 5),
            'slug' => $this->faker->uuid,
            'lock_version' => $this->faker->randomDigit()
        ];
    }
}
