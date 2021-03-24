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
        $data_set = [
            1 => ['user_id' => $user->id],
            2 => ['user_id' => $user->id],
        ];

        foreach ($data_set as $key => $values) {
            CsvImport::updateOrCreate(['id' => $key], $values);
        }
    }
}
