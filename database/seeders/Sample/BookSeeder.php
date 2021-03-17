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
        $user = User::factory()->create();
        $data_set = [
            1 => ['user_id' => $user->id, 'title' => 'Title A'],
            2 => ['user_id' => $user->id, 'title' => 'Title B'],
        ];

        foreach ($data_set as $key => $values) {
            Book::updateOrCreate(['id' => $key], $values);
        }
    }
}
