<?php

// FIXME: SAMPLE CODE

namespace Tests\Unit\Models\Sample;

use App\Models\Sample\Project;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\RefreshSeedDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshSeedDatabase;
    use WithFaker;

    public function testUpdateWithLockVersion()
    {
        /** @var Project $project */
        $project = Project::factory()->create();
        $expected = $project->lock_version + 1;
        $project->update([
            'name' => $this->faker->name,
            'lock_version' => $project->lock_version,
        ]);
        $this->assertEquals($expected, $project->lock_version);
    }

    public function testUpdateWithoutLockVersion()
    {
        /** @var Project $project */
        $project = Project::factory()->create();
        $expected = $project->lock_version + 1;
        $project->update([
            'name' => $this->faker->name,
        ]);
        $this->assertEquals($expected, $project->lock_version);
    }

    public function testUpdateVersionError()
    {
        $this->expectException(ValidationException::class);
        /** @var Project $project */
        $project = Project::factory()->create();
        $expected = $project->lock_version - 1;
        $project->update([
            'name' => $this->faker->name,
            'lock_version' => $expected,
        ]);
    }
}
