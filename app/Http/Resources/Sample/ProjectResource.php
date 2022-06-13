<?php

namespace App\Http\Resources\Sample;

use App\Models\Sample\Project;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Project $this */
        return [
            'id' => $this->id,
            'division_id' => $this->division_id,
            'slug' => $this->slug,
            'name' => $this->name,
            'cover_url' => $this->cover_url,
            'description' => $this->description,
            'priority' => $this->priority,
            'approved' => $this->approved,
            'start_date' => $this->start_date,
            'finished_at' => $this->finished_at,
            'difficulty' => $this->difficulty,
            'coefficient' => $this->coefficient,
            'productivity' => $this->productivity,
            'lock_version' => $this->lock_version,
            'created_at' => $this->created_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
