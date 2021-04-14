<?php

namespace Database\Seeders;

use App\Models\Permission;
use Database\Seeders\Sample\BookSeeder;
use Illuminate\Database\Seeder;
use Throwable;

class DatabaseSeeder extends Seeder
{
    private const BASE_SEEDS = [
        RoleSeeder::class,
        Permission::class,
    ];

    private const DUMMY_SEEDS = [
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
        \DB::transaction(function () {
            $this->call(self::BASE_SEEDS);
            if (!\App::isProduction()) {
                $this->call(self::DUMMY_SEEDS);
            }
        });
    }
}
