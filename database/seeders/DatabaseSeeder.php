<?php

namespace Database\Seeders;

use App;
use Database\Seeders\Auth\InvitationSeeder;
use Database\Seeders\Sample\BookSeeder;
use DB;
use Illuminate\Database\Seeder;
use Throwable;

class DatabaseSeeder extends Seeder
{
    private const BASE_SEEDS = [
        PermissionSeeder::class,
        RoleSeeder::class,
    ];

    private const DUMMY_SEEDS = [
        UserSeeder::class,
        InvitationSeeder::class,
        // FIXME: SAMPLE CODE
        BookSeeder::class,
    ];


    /**
     * Seed the application's database.
     *
     * @return void
     * @throws Throwable
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::transaction(function () {
            $this->call(self::BASE_SEEDS);
            if (!App::isProduction()) {
                $this->call(self::DUMMY_SEEDS);
            }
        });
    }
}
