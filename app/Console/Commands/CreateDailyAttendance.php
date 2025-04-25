<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\Presence;
use App\Models\Leave;
use Carbon\Carbon;

class CreateDailyAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:create-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create daily attendance records for all employees';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        $now = Carbon::now();
        $lateTime = Carbon::createFromTimeString(env('ATTENDANCE_LATE_BEFORE', '10:00'));

        // Get all employees
        $employees = Employee::all();
        $count = 0;
        $updatedCount = 0;

        foreach ($employees as $employee) {
            // Get or create today's record
            $presence = Presence::where('employee_id', $employee->employee_id)
                ->whereDate('date', $today)
                ->first();

            // Check if employee is on leave
            $onLeave = Leave::where('employee_id', $employee->employee_id)
                ->where('status', 'approved')
                ->where('start_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->exists();

            if (!$presence) {
                // Create new record
                Presence::create([
                    'employee_id' => $employee->employee_id,
                    'date' => $today,
                    'status' => $onLeave ? 'on_leave' : ($now->gte($lateTime) ? 'absent' : 'not_checked_in'),
                    'check_in' => null,
                    'check_out' => null
                ]);
                $count++;
            } else if ($presence->status === 'not_checked_in' && $now->gte($lateTime) && !$onLeave) {
                // Update existing record to absent if past late threshold
                $presence->update(['status' => 'absent']);
                $updatedCount++;
            }
        }

        if ($count > 0) {
            $this->info("Created {$count} new attendance records.");
        }
        if ($updatedCount > 0) {
            $this->info("Updated {$updatedCount} records to absent status.");
        }
        if ($count === 0 && $updatedCount === 0) {
            $this->info("No records needed to be created or updated.");
        }
    }
} 