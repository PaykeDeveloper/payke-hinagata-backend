<?php

namespace Database\Seeders\Common;

use App\Models\Common\Invitation;
use App\Models\User;
use Illuminate\Database\Seeder;

class InvitationSeeder extends Seeder
{
    public function run()
    {
        $count = rand(1, 5);
        for ($i = 0; $i < $count; $i++) {
            $user = User::query()->inRandomOrder()->first();
            Invitation::factory()->for($user, 'createdBy')->create();
        }
    }
}
