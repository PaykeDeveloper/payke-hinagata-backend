<?php

namespace App\Mail\Sample;

use App\Models\Sample\BookComment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

// FIXME: サンプルコードです。
class BookCommentCreated extends Mailable
{
    use Queueable, SerializesModels;

    public BookComment $comment;

    /**
     * Create a new message instance.
     *
     * @param BookComment $comment
     */
    public function __construct(BookComment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.book_comment.created');
    }
}
