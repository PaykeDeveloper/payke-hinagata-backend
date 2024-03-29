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
    public function run()
    {
        $count = rand(1, 5);
        for ($i = 0; $i < $count; $i++) {
            $user = User::query()->inRandomOrder()->first();
            $division = Division::query()->inRandomOrder()->first();
            /** @var Member $member */
            $member = Member::query()->updateOrCreate([
                'user_id' => $user->id,
                'division_id' => $division->id,
            ]);
            $role = MemberRole::all()[array_rand(MemberRole::all())];
            $member->syncRoles($role);
        }
    }
}
