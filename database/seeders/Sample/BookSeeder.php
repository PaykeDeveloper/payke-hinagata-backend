<?php

namespace Database\Seeders\Sample;

use App\Models\Sample\Book;
use Illuminate\Database\Seeder;

// FIXME: サンプルコードです。
class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data_set = [
            1 => ['title' => 'Title A'],
            2 => ['title' => 'Title B'],
        ];

        foreach ($data_set as $key => $values) {
            Book::updateOrCreate(['id' => $key], $values);
        }
    }
}
