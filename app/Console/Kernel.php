<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // FIXME: SAMPLE CODE
        // $schedule->job(new CountProjects())->everyFiveMinutes()->onOneServer()->withoutOverlapping();
        $schedule->call(function () {
            \Log::debug('SAMPLE LOGGING');
        })->name('sample_schedule')->onOneServer()->withoutOverlapping()->everyTenMinutes();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
