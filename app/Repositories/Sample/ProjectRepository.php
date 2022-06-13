<?php

namespace App\Repositories\Sample;

use App\Models\Division\Division;
use App\Models\Sample\Project;

class ProjectRepository
{
    public function store(array $attributes, Division $division): Project
    {
        $project = new Project($attributes);
        $project->division_id = $division->id;
        $project->save();
        if (array_key_exists('cover', $attributes)) {
            $project->saveCover($attributes['cover']);
        }
        return $project->fresh();
    }

    public function update(array $attributes, Project $project): Project
    {
        $project->update($attributes);
        if (array_key_exists('cover', $attributes)) {
            $project->saveCover($attributes['cover']);
        }
        return $project->fresh();
    }
}
