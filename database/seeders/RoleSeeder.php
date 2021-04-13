<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data_set = [
            'Super Admin',
        ];

        foreach ($data_set as $i => $value) {
            Role::updateOrCreate([
                'id' => $i + 1,
            ], [
                'name' => $value,
                'guard_name' => 'web',
            ]);
        }
    }
}
