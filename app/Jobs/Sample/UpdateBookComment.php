<?php

// FIXME: SAMPLE CODE

namespace App\Jobs\Sample;

use App\Models\Sample\BookComment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateBookComment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private BookComment $comment;
    private array $attributes;

    /**
     * Create a new job instance.
     *
     * @param BookComment $comment
     * @param array $attributes
     */
    public function __construct(BookComment $comment, array $attributes)
    {
        $this->comment = $comment;
        $this->attributes = $attributes;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment->update($this->attributes);
    }
}
