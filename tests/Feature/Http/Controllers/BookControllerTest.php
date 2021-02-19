<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Book;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

// FIXME: サンプルコードです。
class BookControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * [正常系] 一覧の取得ができる。
     */
    public function testIndex()
    {
        $book = Book::factory()->create();

        $response = $this->getJson(route('books.index'));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'title' => $book->title,
            ]);
    }

    /**
     * [正常系] 作成ができる。
     */
    public function testStore()
    {
        $data = [
            'title' => 'a',
        ];

        $response = $this->postJson(route('books.store'), $data);
        $response->assertOk()
            ->assertJsonFragment($data);
    }

    /**
     * [正常系] 単体の取得ができる。
     */
    public function testShow()
    {
        $book = Book::factory()->withAuthor()->create();

        $response = $this->getJson(route('books.show', ['book' => $book->id]));

        $response->assertOk()
            ->assertJson([
                'title' => $book->title,
                'author' => $book->author,
            ]);
    }

    /**
     * [正常系] 更新ができる。
     */
    public function testUpdate()
    {
        $book = Book::factory()->create();
        $data = [
            'author' => 'a',
        ];

        $response = $this->patchJson(route('books.update', ['book' => $book->id]), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    /**
     * [正常系] 削除ができる。
     */
    public function testDestroy()
    {
        $book = Book::factory()->create();

        $response = $this->deleteJson(route('books.destroy', ['book' => $book->id]));

        $response->assertNoContent();

        $result = Book::find($book->id);
        $this->assertNull($result);
    }

    /**
     * [異常系] 存在しないIDで取得するとエラーになる。
     */
    public function testShowNotFound()
    {
        $response = $this->getJson(route('books.show', ['book' => -1]));

        $response->assertNotFound();
    }

    /**
     * [異常系] タイトルを設定せず作成するとエラーになる。
     */
    public function testStoreRequiredTitle()
    {
        $data = [];

        $response = $this->postJson(route('books.store'), $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['errors' => ['title']]);
    }

    /**
     * [異常系] タイトルをNULLで更新するとエラーになる。
     */
    public function testUpdateNullTitle()
    {
        $book = Book::factory()->create();
        $data = [
            'title' => null,
        ];

        $response = $this->patchJson(route('books.update', ['book' => $book->id]), $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['errors' => ['title']]);
    }
}
