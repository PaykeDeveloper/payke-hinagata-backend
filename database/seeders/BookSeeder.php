<?php

namespace Database\Seeders;

use App\Models\Book;
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
        Book::updateOrCreate(['id' => 1], ['title' => 'Title 1']);
        Book::updateOrCreate(['id' => 2], ['title' => 'Title 2']);
    }
}
