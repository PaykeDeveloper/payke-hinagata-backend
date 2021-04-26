<?php

// FIXME: SAMPLE CODE

namespace Database\Seeders\Sample;

use App\Models\Division\Division;
use App\Models\Sample\DivisionProject;
use Illuminate\Database\Seeder;

class DivisionProjectSeeder extends Seeder
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
            if ($division) {
                DivisionProject::factory()->create(['division_id' => $division->id]);
            }
        }
    }
}
