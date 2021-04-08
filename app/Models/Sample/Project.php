<?php

// FIXME: SAMPLE CODE

namespace App\Models\Sample;

use App\Models\Common\AuthorizableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends AuthorizableModel
{
    use HasFactory;

    protected $guarded = [
        'id',
        'updated_at',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public static function createWithCompany(Company $company, array $attributes)
    {
        $project = new Project();
        $project->fill($attributes);
        $company->projects()->save($project);
        return $project;
    }
}
