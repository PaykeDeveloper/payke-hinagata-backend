<?php

namespace Database\Factories\Sample;

use App\Models\Sample\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

// FIXME: サンプルコードです。
class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->word(),
            'author' => $this->faker->name(),
        ];
    }

    public function requiredOnly(): BookFactory
    {
        return $this->state(fn(array $attributed) => [
            'author' => null,
        ]);
    }
}
