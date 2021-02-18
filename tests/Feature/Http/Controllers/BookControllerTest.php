<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Book;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

// FIXME: サンプルコードです。
class BookControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndex()
    {
        $book = Book::factory()->create();

        $response = $this->get(route('books.index'));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'title' => $book->title,
            ]);
    }

    public function testStore()
    {
        $data = [
            'title' => 'a',
        ];

        $response = $this->post(route('books.store'), $data);
        $response->assertOk()
            ->assertJsonFragment($data);
    }

    public function testShow()
    {
        $book = Book::factory()->withAuthor()->create();

        $response = $this->get(route('books.show', ['book' => $book->id]));

        $response->assertOk()
            ->assertJson([
                'title' => $book->title,
                'author' => $book->author,
            ]);
    }

    public function testUpdate()
    {
        $book = Book::factory()->create();
        $data = [
            'title' => 'a',
        ];

        $response = $this->patch(route('books.update', ['book' => $book->id]), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    public function testDestroy()
    {
        $book = Book::factory()->create();

        $response = $this->delete(route('books.destroy', ['book' => $book->id]));

        $response->assertNoContent();

        $result = Book::find($book->id);
        $this->assertNull($result);
    }
}
