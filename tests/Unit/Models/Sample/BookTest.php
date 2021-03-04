<?php

namespace Tests\Unit\Models\Sample;

use App\Models\Sample\Book;
use App\Models\Sample\BookComment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

// FIXME: サンプルコードです。
class BookTest extends TestCase
{
    use DatabaseMigrations;

    public function testUserSuccess()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($book->user->is($user));
    }

    public function testCommentsSuccess()
    {
        $book = Book::factory()->create();
        $expected = BookComment::factory(['book_id' => $book->id])->create();

        $actual = $book->comments->first();

        $this->assertTrue($actual->is($expected));
    }

    public function testCreateWithUserSuccess()
    {
        $user = User::factory()->create();
        $book = Book::createWithUser(['title' => 'abc'], $user);

        $this->assertNotNull($book);
    }
}
