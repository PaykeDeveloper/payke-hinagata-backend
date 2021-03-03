<?php

namespace Database\Factories\Sample;

use App\Models\Sample\Book;
use App\Models\Sample\BookComment;
use App\Models\Sample\FooBar;
use Illuminate\Database\Eloquent\Factories\Factory;

// FIXME: サンプルコードです。
class BookCommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BookComment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $publish_date = $this->faker->date();
        $approved_at = $this->faker->dateTimeBetween($publish_date, '+6day')
            ->setTimezone(new \DateTimeZone('Asia/Tokyo'))
            ->format(DATE_ATOM);

        return [
            'id' => $this->faker->uuid,
            'book_id' => Book::factory(),
            'confirmed' => $this->faker->boolean(),
            'publish_date' => $publish_date,
            'approved_at' => $approved_at,
            'amount' => strval($this->faker->randomFloat(1, max: 99)),
            'column' => $this->faker->randomFloat(3, max: 9999999),
            'choices' => $this->faker->randomElement(FooBar::all()),
            'description' => $this->faker->paragraph(),
            'votes' => $this->faker->numberBetween(1, 5),
            'slug' => $this->faker->slug(),
        ];
    }
}
