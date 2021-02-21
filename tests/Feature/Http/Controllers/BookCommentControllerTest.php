<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Book;
use App\Models\BookComment;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

// FIXME: サンプルコードです。
class BookCommentControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * [正常系] データ一覧の取得ができる。
     */
    public function testIndex()
    {
        $book = Book::factory()->create();
        $comment = BookComment::factory()->create([
            'book_id' => $book->id,
        ]);

        $response = $this->getJson(route('books.comments.index', ['book' => $book->id]));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment($comment->toArray());
    }

    /**
     * [正常系] 作成ができる。
     */
    public function testStore()
    {
        $book = Book::factory()->create();
        $data = [
            'confirmed' => true,
            'publish_date' => "1971-09-17",
            'approved_at' => "2002-11-19T07:41:55.000000Z",
            'amount' => 95.4,
            'column' => 1073045.344,
            'choices' => "bar",
            'description' => "Consequatur laborum vel quis",
            'votes' => 2,
            'slug' => 'abc',
        ];

        $response = $this->postJson(route('books.comments.store', ['book' => $book->id]), $data);

        $response->assertOk()
            ->assertJsonFragment($data);
    }

    /**
     * [正常系] データの取得ができる。
     */
    public function testShow()
    {
        $comment = BookComment::factory()->create();

        $response = $this->getJson(route(
            'books.comments.show',
            ['book' => $comment->book_id, 'comment' => $comment->id]
        ));

        $response->assertOk()
            ->assertJsonFragment($comment->toArray());
    }

    /**
     * [正常系] 更新ができる。
     */
    public function testUpdate()
    {
        $comment = BookComment::factory()->create();
        $data = [
            'votes' => 1,
        ];

        $response = $this->patchJson(route(
            'books.comments.update',
            ['book' => $comment->book_id, 'comment' => $comment->id]
        ), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    /**
     * [正常系] 削除ができる。
     */
    public function testDestroy()
    {
        $comment = BookComment::factory()->create();

        $response = $this->deleteJson(route(
            'books.comments.destroy',
            ['book' => $comment->book_id, 'comment' => $comment->id]
        ));

        $response->assertNoContent();

        $result = Book::find($comment->id);
        $this->assertNull($result);
    }

    /**
     * [異常系] 存在しないIDで取得するとエラーになる。
     */
    public function testIndexNotFound()
    {
        $response = $this->getJson(route('books.comments.index', ['book' => -1]));

        $response->assertNotFound();
    }

    /**
     * [正常系] 存在しないIDで取得するとエラーになる。
     */
    public function testStoreNotFound()
    {
        $data = [
            'confirmed' => true,
            'publish_date' => "1971-09-17",
            'approved_at' => "2002-11-19T07:41:55.000000Z",
            'amount' => 95.4,
            'column' => 1073045.344,
            'choices' => "bar",
            'description' => "Consequatur laborum vel quis",
            'votes' => 2,
            'slug' => 'abc',
        ];

        $response = $this->postJson(route('books.comments.store', ['book' => -1]), $data);

        $response->assertNotFound();
    }

    /**
     * [異常系] 公開日を設定せず作成するとエラーになる。
     */
    public function testStoreRequiredPublishDate()
    {
        $book = Book::factory()->create();
        $data = [
            'confirmed' => true,
//            'publish_date' => "1971-09-17",
            'approved_at' => "2002-11-19T07:41:55.000000Z",
            'amount' => 95.4,
            'column' => 1073045.344,
            'choices' => "bar",
            'description' => "Consequatur laborum vel quis",
            'votes' => 2,
            'slug' => 'abc',
        ];

        $response = $this->postJson(route('books.comments.store', ['book' => $book->id]), $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['errors' => ['publish_date']]);
    }

    /**
     * [異常系] 存在しないIDで取得するとエラーになる。
     */
    public function testShowNotFound()
    {
        $comment = BookComment::factory()->create();
        $another_book = Book::factory()->create();
        $patterns = [
            ['book' => $comment->book_id, 'comment' => '881d5f10-34be-448b-aede-d84ec6c5919b'],
            ['book' => -1, 'comment' => $comment->id],
            ['book' => $another_book->id, 'comment' => $comment->id],
        ];

        foreach ($patterns as $pattern) {
            $response = $this->getJson(route('books.comments.show', $pattern));

            $response->assertNotFound();
        }
    }

    /**
     * [異常系] 存在しないIDで更新するとエラーになる。
     */
    public function testUpdateNotFound()
    {
        $comment = BookComment::factory()->create();
        $another_book = Book::factory()->create();
        $patterns = [
            ['book' => $comment->book_id, 'comment' => '881d5f10-34be-448b-aede-d84ec6c5919b'],
            ['book' => -1, 'comment' => $comment->id],
            ['book' => $another_book->id, 'comment' => $comment->id],
        ];
        $data = [
            'votes' => 1,
        ];

        foreach ($patterns as $pattern) {
            $response = $this->patchJson(route('books.comments.update', $pattern), $data);

            $response->assertNotFound();
        }
    }

    /**
     * [異常系] 得票をNULLで更新するとエラーになる。
     */
    public function testUpdateNullVotes()
    {
        $comment = BookComment::factory()->create();
        $data = [
            'votes' => null,
        ];

        $response = $this->patchJson(route(
            'books.comments.update',
            ['book' => $comment->book_id, 'comment' => $comment->id]
        ), $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['errors' => ['votes']]);
    }

    /**
     * [異常系] 存在しないIDで削除するとエラーになる。
     */
    public function testDeleteNotFound()
    {
        $comment = BookComment::factory()->create();
        $another_book = Book::factory()->create();
        $patterns = [
            ['book' => $comment->book_id, 'comment' => '881d5f10-34be-448b-aede-d84ec6c5919b'],
            ['book' => -1, 'comment' => $comment->id],
            ['book' => $another_book->id, 'comment' => $comment->id],
        ];

        foreach ($patterns as $pattern) {
            $response = $this->deleteJson(route('books.comments.destroy', $pattern));

            $response->assertNotFound();
        }
    }
}
