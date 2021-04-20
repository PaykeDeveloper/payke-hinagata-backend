<?php

namespace Database\Seeders;

use App\Models\Common\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory()->count(rand(1, 5))->create();
        /** @var User $user */
        foreach ($users as $user) {
            $user->syncRoles(array_rand(UserRole::all()));
        }
    }
}
