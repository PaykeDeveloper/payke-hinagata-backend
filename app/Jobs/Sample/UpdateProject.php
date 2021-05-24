<?php

// FIXME: SAMPLE CODE

namespace App\Jobs\Sample;

use App\Models\Sample\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateProject implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Project $project;
    private array $attributes;

    /**
     * Create a new job instance.
     *
     * @param Project $project
     * @param mixed $attributes
     */
    public function __construct(Project $project, mixed $attributes)
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
        $this->project->updateFromRequest($this->attributes);
    }
}
