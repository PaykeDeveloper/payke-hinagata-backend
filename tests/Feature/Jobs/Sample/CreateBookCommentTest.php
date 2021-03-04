<?php

namespace Tests\Feature\Jobs\Sample;

use App\Jobs\Sample\CreateBookComment;
use App\Models\Sample\Book;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

// FIXME: サンプルコードです。
class CreateBookCommentTest extends TestCase
{
    use DatabaseMigrations;

    public function testDispatchSuccess()
    {
        $book = Book::factory()->create();
        $attributes = ['slug' => 'abc'];

        $response = CreateBookComment::dispatchSync($book, $attributes);

        $this->assertEquals(0, $response);
    }
}
