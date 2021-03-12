<?php

// FIXME: SAMPLE CODE

namespace Tests\Feature\Http\Controllers\Sample;

use App\Models\Sample\Book;
use App\Models\Sample\BookComment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use DatabaseMigrations;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * [正常系]
     */

    /**
     * データ一覧の取得ができる。
     */
    public function testIndexSuccess()
    {
        $book = Book::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson(route('books.index'));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment($book->toArray());
    }

    /**
     * 作成ができる。
     */
    public function testStoreSuccess()
    {
        $data = ['title' => 'a'];

        $response = $this->postJson(route('books.store'), $data);

        $response->assertOk()
            ->assertJsonFragment($data);
    }

    /**
     * データの取得ができる。
     */
    public function testShowSuccess()
    {
        $book = Book::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson(route('books.show', ['book' => $book->id]));

        $response->assertOk()
            ->assertJson($book->toArray());
    }

    /**
     * 更新ができる。
     */
    public function testUpdateSuccess()
    {
        $book = Book::factory()->requiredOnly()->create(['user_id' => $this->user->id]);

        $data = [
            'author' => 'a',
        ];

        $response = $this->patchJson(route('books.update', ['book' => $book->id]), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    /**
     * 削除ができる。
     */
    public function testDestroySuccess()
    {
        $book = Book::factory()->create(['user_id' => $this->user->id]);
        BookComment::factory()->create(['book_id' => $book->id]);

        $response = $this->deleteJson(route('books.destroy', ['book' => $book->id]));

        $response->assertNoContent();

        $result = Book::find($book->id);
        $this->assertNull($result);
    }

    /**
     * [準正常系]
     */

    /**
     * ユーザーに紐づかないデータは取得されない。
     */
    public function testIndexEmpty()
    {
        Book::factory()->create();

        $response = $this->getJson(route('books.index'));

        $response->assertOk()
            ->assertJsonCount(0);
    }

    /**
     * ユーザーに紐づかないIDで取得するとエラーになる。
     */
    public function testShowNotFound()
    {
        $book = Book::factory()->create();

        $response = $this->getJson(route('books.show', ['book' => $book->id]));

        $response->assertNotFound();
    }

    /**
     * タイトルを設定せず作成するとエラーになる。
     */
    public function testStoreRequiredTitle()
    {
        $data = [];

        $response = $this->postJson(route('books.store'), $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['errors' => ['title']]);
    }

    /**
     * ユーザーに紐づかないIDで更新するとエラーになる。
     */
    public function testUpdateNotFound()
    {
        $book = Book::factory()->create();
        $data = ['author' => 'a'];

        $response = $this->patchJson(route('books.update', ['book' => $book->id]), $data);

        $response->assertNotFound();
    }

    /**
     * タイトルをNULLで更新するとエラーになる。
     */
    public function testUpdateNullTitle()
    {
        $book = Book::factory()->create(['user_id' => $this->user->id]);
        $data = ['title' => null];

        $response = $this->patchJson(route('books.update', ['book' => $book->id]), $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['errors' => ['title']]);
    }

    /**
     * ユーザーに紐づかないIDで削除するとエラーになる。
     */
    public function testDeleteNotFound()
    {
        $book = Book::factory()->create();

        $response = $this->deleteJson(route('books.destroy', ['book' => $book->id]));

        $response->assertNotFound();
    }
}
