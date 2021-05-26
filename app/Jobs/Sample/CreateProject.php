<?php

// FIXME: SAMPLE CODE

namespace App\Jobs\Sample;

use App\Mail\Sample\ProjectCreated;
use App\Models\Division\Division;
use App\Models\Sample\Project;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateProject implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Division $division;
    private array $attributes;
    private User $user;

    /**
     * Create a new job instance.
     *
     * @param Division $division
     * @param mixed $attributes
     * @param User $user
     */
    public function __construct(Division $division, mixed $attributes, User $user)
    {
        $this->division = $division;
        $this->attributes = $attributes;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $project = Project::createFromRequest($this->attributes, $this->division);
        \Mail::to($this->user)->send(new ProjectCreated($project));
    }
}
