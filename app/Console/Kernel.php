<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('punches:import')->everyFifteenMinutes();
        $schedule->command('punches:medical-leave-entry')->hourly();
        $schedule->command('punches:calculate-duration')->everyTenMinutes();
        $schedule->command('punches:sync-device-logs')->everyFiveMinutes();
        $schedule->command('punches:set-latemark')->everyThirtyMinutes();
        // $schedule->command('punches:complimentary-leave')->everySixHours();
        // $schedule->command('app:database-backup')->at('03:00')->days([Schedule::SUNDAY, Schedule::TUESDAY, Schedule::THURSDAY, Schedule::SATURDAY]);
        $schedule->command('app:database-backup')->dailyAt('03:00');
        $schedule->command('app:rectify-punches '.Carbon::today()->subDay()->toDateString().' y')->dailyAt('02:00');
        $schedule->command('app:remove-duplicate')->dailyAt('03:40');
        $schedule->command('punches:manual-sync')->everyFifteenMinutes();

        // $schedule->command('app:sync-device-logs-test')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
