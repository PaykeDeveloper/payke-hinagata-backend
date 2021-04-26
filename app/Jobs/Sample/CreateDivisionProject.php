<?php

// FIXME: SAMPLE CODE

namespace App\Jobs\Sample;

use App\Models\Division\Division;
use App\Models\Sample\DivisionProject;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateDivisionProject implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Division $division;
    private array $attributes;

    /**
     * Create a new job instance.
     *
     * @param Division $book
     * @param mixed $attributes
     */
    public function __construct(Division $book, mixed $attributes)
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
        DivisionProject::createFromRequest($this->attributes, $this->book);
    }
}
