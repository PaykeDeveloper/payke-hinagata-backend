<?php

namespace Database\Seeders;

use Database\Seeders\Sample\BookSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // FIXME: SAMPLE CODE
        $this->call(BookSeeder::class);
    }
}
