<?php

// FIXME: SAMPLE CODE

namespace Database\Seeders\Sample;

use App\Models\Sample\Book;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = rand(1, 5);
        for ($i = 0; $i < $count; $i++) {
            $user = User::inRandomOrder()->first();
            Book::factory()->create(['user_id' => $user->id]);
        }
    }
}
