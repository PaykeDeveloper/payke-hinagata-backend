<?php

// FIXME: SAMPLE CODE

namespace App\Mail\Sample;

use App\Models\Sample\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectCreated extends Mailable
{
    use Queueable, SerializesModels;

    public Project $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function build(): static
    {
        return $this->view('emails.project.created');
    }
}
