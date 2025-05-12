<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\Presence;
use App\Models\Holiday;
use Carbon\Carbon;

class MarkAllHolidaysForAllEmployees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'presence:mark-all-holidays-for-all-employees {year?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark all employees as Libur for all holidays in the given year (or current year)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $year = $this->argument('year') ?? date('Y');
        $holidays = Holiday::whereYear('date', $year)->get();
        $employees = Employee::all();
        $total = 0;

        foreach ($holidays as $holiday) {
            foreach ($employees as $employee) {
                Presence::updateOrCreate(
                    [
                        'employee_id' => $employee->employee_id,
                        'date' => $holiday->date->toDateString(),
                    ],
                    [
                        'status' => 'libur',
                        'check_in' => null,
                        'check_out' => null,
                    ]
                );
                $total++;
            }
        }

        $this->info("Marked all employees as 'Libur' for all holidays in $year. Total records updated/created: $total");
    }
}
