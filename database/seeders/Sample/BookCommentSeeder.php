<?php

// FIXME: SAMPLE CODE

namespace Database\Seeders\Sample;

use App\Models\Sample\Book;
use App\Models\Sample\BookComment;
use Illuminate\Database\Seeder;

class BookCommentSeeder extends Seeder
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
            $book = Book::inRandomOrder()->first();
            if ($book) {
                BookComment::factory()->create(['book_id' => $book->id]);
            }
        }
    }
}
