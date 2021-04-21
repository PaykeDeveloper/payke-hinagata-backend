<?php

// FIXME: SAMPLE CODE

namespace Database\Seeders\Sample;

use App\Models\Division\Division;
use App\Models\Sample\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = rand(1, 5);
        for ($i = 0; $i < $count; $i++) {
            $division = Division::inRandomOrder()->first();
            Project::factory()->create(['division_id' => $division->id]);
        }
    }
}
