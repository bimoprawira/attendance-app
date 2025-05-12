<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\Presence;
use App\Models\Holiday;
use Carbon\Carbon;

class MarkHolidayPresenceForAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'presence:mark-holiday-for-all {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically mark all employees as Libur if today (or given date) is a holiday';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->argument('date') ?? Carbon::today()->toDateString();

        if (!Holiday::isHoliday($date)) {
            $this->info("$date is not a holiday. No action taken.");
            return;
        }

        $employees = Employee::all();
        $count = 0;

        foreach ($employees as $employee) {
            Presence::updateOrCreate(
                [
                    'employee_id' => $employee->employee_id,
                    'date' => $date,
                ],
                [
                    'status' => 'libur',
                    'check_in' => null,
                    'check_out' => null,
                ]
            );
            $count++;
        }

        $this->info("Marked $count employees as 'Libur' for $date.");
    }
}
