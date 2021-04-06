<?php

namespace Database\Seeders\User;

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
        $user = User::factory()->create();
        $data_set = [
            1 => 'Super Admin'
        ];

        foreach ($data_set as $key => $value) {
            $user = User::find($key);

            Role::create(['name' => $value]);
            $user->assignRole($value);
        }
    }
}
