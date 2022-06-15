<?php

// FIXME: SAMPLE CODE

namespace App\Jobs\Sample;

use App\Models\Sample\Project;
use App\Repositories\Sample\ProjectRepository;
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

    public function __construct(Project $project, mixed $attributes)
    {
        $this->project = $project;
        $this->attributes = $attributes;
    }

    public function handle(ProjectRepository $repository): void
    {
        $repository->update($this->attributes, $this->project);
    }
}
