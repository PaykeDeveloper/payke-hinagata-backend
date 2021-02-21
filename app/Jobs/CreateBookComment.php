<?php

namespace App\Jobs;

use App\Models\Book;
use App\Models\BookComment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

// FIXME: サンプルコードです。
class CreateBookComment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Book $book;
    private array $attributes;

    /**
     * Create a new job instance.
     *
     * @param Book $book
     * @param array $attributes
     */
    public function __construct(Book $book, array $attributes)
    {
        $this->book = $book;
        $this->attributes = $attributes;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $comment = new BookComment();
        $comment->fill($this->attributes);
        $comment->book_id = $this->book->id;
        $comment->save();
    }
}