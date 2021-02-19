<?php

namespace Database\Factories;

use App\Models\Book;
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
            'title' => $this->faker->word(),
        ];
    }

    public function withAuthor(): BookFactory
    {
        return $this->state(fn(array $attributed) => [
            'author' => $this->faker->name(),
        ]);
    }
}