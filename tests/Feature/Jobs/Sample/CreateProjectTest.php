<?php

// FIXME: SAMPLE CODE

namespace Tests\Feature\Jobs\Sample;

use App\Jobs\Sample\CreateProject;
use App\Models\Division\Division;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\RefreshSeedDatabase;
use Tests\TestCase;

class CreateProjectTest extends TestCase
{
    use RefreshSeedDatabase;
    use WithFaker;

    public function testDispatchSuccess()
    {
        $division = Division::factory()->create();
        $attributes = ['name' => $this->faker->name];

        $response = CreateProject::dispatchSync($division, $attributes);

        $this->assertEquals(0, $response);
    }
}
