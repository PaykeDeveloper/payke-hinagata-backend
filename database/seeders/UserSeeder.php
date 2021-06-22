<?php

namespace Database\Seeders;

use App\Models\Common\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = User::factory()->count(rand(1, 5))->create();
        /** @var User $user */
        foreach ($users as $user) {
            $role = UserRole::all()[array_rand(UserRole::all())];
            $user->syncRoles($role);
        }
    }
}
