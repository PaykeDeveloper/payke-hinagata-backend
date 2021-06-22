<?php

// FIXME: SAMPLE CODE

namespace Database\Seeders\Division;

use App\Models\Division\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    public function run()
    {
        Division::factory()->count(rand(1, 5))->create();
    }
}
