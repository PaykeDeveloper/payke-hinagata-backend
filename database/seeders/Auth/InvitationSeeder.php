<?php

namespace Database\Seeders\Auth;

use App\Models\Auth\Invitation;
use App\Models\User;
use Illuminate\Database\Seeder;

class InvitationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first() ?? User::factory()->create();
        Invitation::factory()->count(3)->create(['user_id' => $user->id]);
    }
}
