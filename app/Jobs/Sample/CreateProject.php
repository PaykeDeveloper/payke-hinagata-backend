<?php

// FIXME: SAMPLE CODE

namespace App\Jobs\Sample;

use App\Models\Division\Division;
use App\Models\Sample\Project;
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

    /**
     * Create a new job instance.
     *
     * @param Division $division
     * @param mixed $attributes
     */
    public function __construct(Division $division, mixed $attributes)
    {
        $this->division = $division;
        $this->attributes = $attributes;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Project::createFromRequest($this->attributes, $this->division);
    }
}
