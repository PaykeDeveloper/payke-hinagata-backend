<?php

namespace Database\Seeders\Sample;

use App\Models\Sample\CsvImport;
use App\Models\User;
use Illuminate\Database\Seeder;

class CsvImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create();
        CsvImport::factory()->count(50)->create(['user_id' => $user->id]);
    }
}
