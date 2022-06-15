<?php

// FIXME: SAMPLE CODE

namespace Database\Seeders\Sample;

use App\Models\Division\Division;
use App\Models\Sample\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        $count = rand(1, 5);
        for ($i = 0; $i < $count; $i++) {
            $division = Division::query()->inRandomOrder()->first();
            Project::factory()->for($division)->create();
        }
    }
}
