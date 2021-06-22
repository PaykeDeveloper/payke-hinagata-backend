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
use Mail;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

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

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function handle(): void
    {
        $project = Project::createFromRequest($this->attributes, $this->division);
        Mail::to($this->user)->send(new ProjectCreated($project));
    }
}
