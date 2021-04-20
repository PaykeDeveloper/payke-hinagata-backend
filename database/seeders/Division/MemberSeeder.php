<?php

// FIXME: SAMPLE CODE

namespace Database\Seeders\Division;

use App\Models\Division\Division;
use App\Models\Division\Member;
use App\Models\Division\MemberRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = rand(1, 5);
        for ($i = 0; $i < $count; $i++) {
            $user = User::inRandomOrder()->first();
            $division = Division::inRandomOrder()->first();
            /** @var Member $member */
            $member = Member::updateOrCreate([
                'user_id' => $user->id,
                'division_id' => $division->id,
            ]);
            $member->syncRoles(array_rand(MemberRole::all()));
        }
    }
}
