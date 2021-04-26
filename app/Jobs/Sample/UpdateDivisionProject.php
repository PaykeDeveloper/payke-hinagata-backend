<?php

// FIXME: SAMPLE CODE

namespace App\Jobs\Sample;

use App\Models\Sample\DivisionProject;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateDivisionProject implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private DivisionProject $project;
    private array $attributes;

    /**
     * Create a new job instance.
     *
     * @param DivisionProject $project
     * @param mixed $attributes
     */
    public function __construct(DivisionProject $project, mixed $attributes)
    {
        $this->project = $project;
        $this->attributes = $attributes;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment->updateFromRequest($this->attributes);
    }
}
