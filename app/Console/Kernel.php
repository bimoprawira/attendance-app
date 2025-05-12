<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\CreateDailyAttendance::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Fetch holidays for the next year every December 1st
        $schedule->command('holidays:fetch ' . (date('Y') + 1))
                ->yearly()
                ->on('December 1')
                ->at('00:00');
                
        // Update current year holidays every month to catch any changes
        $schedule->command('holidays:fetch ' . date('Y'))
                ->monthly()
                ->firstDayAt('00:00');

        // Run every hour between 10:00 and 23:00
        $schedule->command('attendance:create-daily')
                ->hourly()
                ->between('10:00', '23:00');

        $schedule->command('presence:mark-holiday-for-all')->dailyAt('00:10');
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