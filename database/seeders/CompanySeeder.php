<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = Company::updateOrCreate(['id' => 1]);

        // $user = User::factory()->create();
        $data_set = [
            1 => ['staff_id' => $user->id, 'title' => 'Title A'],
            2 => ['user_id' => $user->id, 'title' => 'Title B'],
        ];

        foreach ($data_set as $key => $values) {
            Book::updateOrCreate(['id' => $key], $values);
        }
    }
}
