<?php

// FIXME: SAMPLE CODE

namespace App\Jobs\Sample;

use App\Models\Sample\Book;
use App\Models\Sample\BookComment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateBookComment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Book $book;
    private array $attributes;

    /**
     * Create a new job instance.
     *
     * @param Book $book
     * @param mixed $attributes
     */
    public function __construct(Book $book, mixed $attributes)
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
        BookComment::createWithBook($this->attributes, $this->book);
    }
}
