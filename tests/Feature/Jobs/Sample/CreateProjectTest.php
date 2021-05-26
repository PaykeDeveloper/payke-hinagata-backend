<?php

// FIXME: SAMPLE CODE

namespace Tests\Feature\Jobs\Sample;

use App\Jobs\Sample\CreateProject;
use App\Models\Division\Division;
use App\Models\User;
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
        $user = User::factory()->create();

        $response = CreateProject::dispatchSync($division, $attributes, $user);

        $this->assertEquals(0, $response);
    }
}
