<?php

// FIXME: SAMPLE CODE

namespace Tests\Feature\Jobs\Sample;

use App\Jobs\Sample\CreateProject;
use App\Models\Division\Division;
use Tests\RefreshSeedDatabase;
use Tests\TestCase;

class CreateProjectTest extends TestCase
{
    use RefreshSeedDatabase;

    public function testDispatchSuccess()
    {
        $division = Division::factory()->create();
        $attributes = ['slug' => 'abc'];

        $response = CreateProject::dispatchSync($division, $attributes);

        $this->assertEquals(0, $response);
    }
}
