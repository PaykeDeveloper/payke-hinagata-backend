<?php

namespace App\Http\Resources\Sample;

use App\Models\Sample\Project;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Project $project */
        $project = $this->resource;
        return [
            'id' => $project->id,
            'division_id' => $project->division_id,
            'slug' => $project->slug,
            'name' => $project->name,
            'cover_url' => $project->cover_url,
            'description' => $project->description,
            'priority' => $project->priority,
            'approved' => $project->approved,
            'start_date' => $project->start_date,
            'finished_at' => $project->finished_at,
            'difficulty' => $project->difficulty,
            'coefficient' => $project->coefficient,
            'productivity' => $project->productivity,
            'lock_version' => $project->lock_version,
            'created_at' => $project->created_at,
            'deleted_at' => $project->deleted_at,
        ];
    }
}
