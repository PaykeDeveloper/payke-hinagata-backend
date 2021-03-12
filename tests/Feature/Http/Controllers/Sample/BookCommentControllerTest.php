<?php

// FIXME: SAMPLE CODE

namespace Tests\Feature\Http\Controllers\Sample;

use App\Models\Sample\Book;
use App\Models\Sample\BookComment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class BookCommentControllerTest extends TestCase
{
    use DatabaseMigrations;

    private Book $book;

    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->book = Book::factory()->create(['user_id' => $user->id]);
    }

    /**
     * [正常系]
     */

    /**
     * データ一覧の取得ができる。
     */
    public function testIndexSuccess()
    {
        $comment = BookComment::factory()->create(['book_id' => $this->book->id]);

        $response = $this->getJson(route('books.comments.index', ['book' => $comment->book_id]));

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment($comment->toArray());
    }

    /**
     * 作成ができる。
     */
    public function testStoreSuccess()
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

        $response = $this->postJson(route('books.comments.store', ['book' => $this->book->id]), $data);

        $response->assertOk()
            ->assertJsonFragment($data);
    }

    /**
     * 作成ができる。
     */
    public function testStoreAsyncSuccess()
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

        $response = $this->postJson("/api/v1/books/{$this->book->id}/comments/create-async", $data);

        $response->assertNoContent();
    }

    /**
     * データの取得ができる。
     */
    public function testShowSuccess()
    {
        $comment = BookComment::factory()->create(['book_id' => $this->book->id]);

        $response = $this->getJson(route(
            'books.comments.show',
            ['book' => $comment->book_id, 'comment' => $comment->id]
        ));

        $response->assertOk()
            ->assertJsonFragment($comment->toArray());
    }

    /**
     * 更新ができる。
     */
    public function testUpdateSuccess()
    {
        $comment = BookComment::factory()->create(['book_id' => $this->book->id]);
        $data = ['votes' => 1];

        $response = $this->patchJson(route(
            'books.comments.update',
            ['book' => $comment->book_id, 'comment' => $comment->id]
        ), $data);

        $response->assertOk()
            ->assertJson($data);
    }

    /**
     * 更新ができる。
     */
    public function testUpdateAsyncSuccess()
    {
        $comment = BookComment::factory()->create(['book_id' => $this->book->id]);
        $data = ['votes' => 1];

        $response = $this->patchJson("/api/v1/books/{$comment->book_id}/comments/{$comment->id}/update-async", $data);

        $response->assertNoContent();
    }

    /**
     * 削除ができる。
     */
    public function testDestroySuccess()
    {
        $comment = BookComment::factory()->create(['book_id' => $this->book->id]);

        $response = $this->deleteJson(route(
            'books.comments.destroy',
            ['book' => $comment->book_id, 'comment' => $comment->id]
        ));

        $response->assertNoContent();
        $result = Book::find($comment->id);
        $this->assertNull($result);
    }

    /**
     * [準正常系]
     */

    /**
     * ユーザーに紐づかないデータを取得するとエラーになる。
     */
    public function testIndexNotFound()
    {
        $comment = BookComment::factory()->create();

        $response = $this->getJson(route('books.comments.index', ['book' => $comment->book_id]));

        $response->assertNotFound();
    }

    /**
     * ユーザーに紐づかないデータを作成するとエラーになる。
     */
    public function testStoreNotFound()
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

        $response->assertNotFound();
    }

    /**
     * Slugを設定せず作成するとエラーになる。
     */
    public function testStoreRequiredSlug()
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
//            'slug' => 'abc',
        ];

        $response = $this->postJson(route('books.comments.store', ['book' => $this->book->id]), $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['errors' => ['slug']]);
    }

    /**
     * Slugが重複する場合はエラーになる。
     */
    public function testStoreDuplicateSlug()
    {
        $bookComment = BookComment::factory()->create();
        $data = [
            'confirmed' => true,
            'publish_date' => "1971-09-17",
            'approved_at' => "2002-11-19T07:41:55.000000Z",
            'amount' => 95.4,
            'column' => 1073045.344,
            'choices' => "bar",
            'description' => "Consequatur laborum vel quis",
            'votes' => 2,
            'slug' => $bookComment->slug,
        ];

        $response = $this->postJson(route('books.comments.store', ['book' => $this->book->id]), $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['errors' => ['slug']]);
    }

    /**
     * ユーザーに紐づかないIDで取得するとエラーになる。
     */
    public function testShowNotFound()
    {
        $patterns = $this->createNotFoundPatterns();

        foreach ($patterns as $pattern) {
            $response = $this->getJson(route('books.comments.show', $pattern));

            $response->assertNotFound();
        }
    }

    /**
     * ユーザーに紐づかないIDで更新するとエラーになる。
     */
    public function testUpdateNotFound()
    {
        $patterns = $this->createNotFoundPatterns();
        $data = ['votes' => 1];

        foreach ($patterns as $pattern) {
            $response = $this->patchJson(route('books.comments.update', $pattern), $data);

            $response->assertNotFound();
        }
    }

    /**
     * SlugをNULLで更新するとエラーになる。
     */
    public function testUpdateNullSlug()
    {
        $comment = BookComment::factory()->create(['book_id' => $this->book->id]);
        $data = ['slug' => null];

        $response = $this->patchJson(route(
            'books.comments.update',
            ['book' => $comment->book_id, 'comment' => $comment->id]
        ), $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['errors' => ['slug']]);
    }

    /**
     * ユーザーに紐づかないIDで削除するとエラーになる。
     */
    public function testDeleteNotFound()
    {
        $patterns = $this->createNotFoundPatterns();

        foreach ($patterns as $pattern) {
            $response = $this->deleteJson(route('books.comments.destroy', $pattern));

            $response->assertNotFound();
        }
    }


    private function createNotFoundPatterns(): array
    {
        $comment = BookComment::factory()->create(['book_id' => $this->book->id]);
        $another_book = Book::factory()->create(['user_id' => $this->book->user->id]);
        $another_book_comment = BookComment::factory()->create(['book_id' => $another_book->id]);
        $another_user_comment = BookComment::factory()->create();
        return [
            ['book' => $comment->book_id, 'comment' => $another_book_comment->id],
            ['book' => $another_book->id, 'comment' => $comment->id],
            ['book' => $another_user_comment->book_id, 'comment' => $another_user_comment->id],
        ];
    }
}
