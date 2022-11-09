<?php

// FIXME: SAMPLE CODE

namespace App\Jobs\Sample;

use App\Mail\Sample\ProjectCreated;
use App\Models\Division\Division;
use App\Models\User;
use App\Repositories\Sample\ProjectRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class CreateProject implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Division $division;

    private array $attributes;

    private User $user;

    public function __construct(Division $division, mixed $attributes, User $user)
    {
        $this->division = $division;
        $this->attributes = $attributes;
        $this->user = $user;
    }

    public function handle(ProjectRepository $repository): void
    {
        $project = $repository->store($this->attributes, $this->division);
        Mail::to($this->user)->send(new ProjectCreated($project));
    }
}
