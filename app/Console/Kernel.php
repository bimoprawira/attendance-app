<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\CreateDailyAttendance::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Run every hour between 10:00 and 23:00
        $schedule->command('attendance:create-daily')
                ->hourly()
                ->between('10:00', '23:00');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
} 